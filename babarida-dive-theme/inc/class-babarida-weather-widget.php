<?php
/**
 * Weather API Integration & Cache
 * Uses OpenWeatherMap API structure
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

namespace Babarida_Theme;

if (!defined('ABSPATH')) {
    exit;
}

class Babarida_Weather_Widget
{

    private string $api_url = 'https://api.openweathermap.org/data/2.5/weather';
    private array $locations = array(
        'manado' => array(
            'lat' => 1.4748,
            'lon' => 124.8421,
            'name' => 'Manado / Bunaken',
        ),
        'lembeh' => array(
            'lat' => 1.5833,
            'lon' => 125.2333,
            'name' => 'Lembeh Strait',
        ),
        'bangka' => array(
            'lat' => 1.6833,
            'lon' => 125.1333,
            'name' => 'Bangka Island',
        ),
    );

    public function __construct()
    {
        // Schedule cron if not exists
        if (!wp_next_scheduled('babarida_update_weather_cache')) {
            wp_schedule_event(time(), 'hourly', 'babarida_update_weather_cache');
        }
        add_action('babarida_update_weather_cache', [$this, 'update_all_caches']);
    }

    /**
     * Get cached weather for a location
     */
    public function get_cached_weather(string $location_key): ?array
    {
        global $wpdb;
        $table = $wpdb->prefix . 'babarida_weather_cache';

        $cached = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT data_json, expires_at FROM {$table} WHERE location = %s",
                $location_key
            ),
            OBJECT
        );

        if ($cached) {
            if (strtotime($cached->expires_at) > time()) {
                return json_decode($cached->data_json, true);
            }
        }

        // Fetch fresh data
        return $this->fetch_and_cache($location_key);
    }

    /**
     * Fetch from API and store in cache
     */
    private function fetch_and_cache(string $location_key): ?array
    {
        if (!isset($this->locations[$location_key])) {
            return null;
        }

        $loc   = $this->locations[$location_key];
        $api_key = get_option('babarida_openweather_api_key', '');

        if (empty($api_key)) {
            // Return mock data for development if no API key
            return $this->get_mock_data($location_key);
        }

        $url = add_query_arg(
            array(
                'lat'             => $loc['lat'],
                'lon'             => $loc['lon'],
                'appid'           => $api_key,
                'units'           => 'metric',
                'exclude'         => 'minutely,hourly,daily,alerts',
            ),
            $this->api_url
        );

        $response = wp_remote_get($url, array('timeout' => 10));

        if (is_wp_error($response)) {
            return null;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($data['main'])) {
            return null;
        }

        $formatted = array(
            'location'     => $loc['name'],
            'temp'         => round($data['main']['temp']),
            'feels_like'   => round($data['main']['feels_like']),
            'temp_min'     => round($data['main']['temp_min']),
            'temp_max'     => round($data['main']['temp_max']),
            'humidity'     => $data['main']['humidity'],
            'wind_speed'   => round($data['wind']['speed'] * 1.944, 1), // m/s to knots
            'wind_deg'     => $data['wind']['deg'] ?? 0,
            'description'  => $data['weather'][0]['description'] ?? '',
            'icon'         => $data['weather'][0]['icon'] ?? '01d',
            'visibility'   => ($data['visibility'] ?? 10000) / 1000, // meters to km
            'pressure'     => $data['main']['pressure'] ?? 1013,
            'fetched_at'   => current_time('mysql'),
        );

        $this->save_cache($location_key, $formatted);

        return $formatted;
    }

    /**
     * Save to database cache
     */
    private function save_cache(string $location_key, array $data): void
    {
        global $wpdb;
        $table = $wpdb->prefix . 'babarida_weather_cache';

        $wpdb->replace(
            $table,
            array(
                'location'    => $location_key,
                'latitude'    => $this->locations[$location_key]['lat'],
                'longitude'   => $this->locations[$location_key]['lon'],
                'data_json'   => wp_json_encode($data),
                'fetched_at'  => current_time('mysql'),
                'expires_at'  => gmdate('Y-m-d H:i:s', time() + 3600), // 1 hour cache
            ),
            array('%s', '%f', '%f', '%s', '%s', '%s')
        );
    }

    /**
     * Update all location caches
     */
    public function update_all_caches(): void
    {
        foreach (array_keys($this->locations) as $key) {
            $this->fetch_and_cache($key);
        }
    }

    /**
     * Mock data for development
     */
    private function get_mock_data(string $location_key): array
    {
        $loc = $this->locations[$location_key] ?? array('name' => $location_key);
        
        return array(
            'location'    => $loc['name'],
            'temp'        => 29,
            'feels_like'  => 32,
            'temp_min'    => 28,
            'temp_max'    => 30,
            'humidity'    => 78,
            'wind_speed'  => 8.5,
            'wind_deg'    => 135,
            'description' => 'scattered clouds',
            'icon'        => '02d',
            'visibility'  => 10,
            'pressure'    => 1010,
            'fetched_at'  => current_time('mysql'),
        );
    }
}
