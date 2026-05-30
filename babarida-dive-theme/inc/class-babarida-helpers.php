<?php
/**
 * Helper Functions Class
 * Interacts with custom DB tables
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

namespace Babarida_Theme;

if (!defined('ABSPATH')) {
    exit;
}

class Babarida_Helpers
{

    /**
     * Get destinations for frontend display
     */
    public static function get_destinations(): \WP_Query
    {
        return new \WP_Query(array(
            'post_type'      => 'destination',
            'posts_per_page' => 4,
            'post_status'    => 'publish',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ));
    }

    /**
     * Get approved testimonials from custom table
     */
    public static function get_testimonials(int $limit = 6): array
    {
        global $wpdb;
        $table   = $wpdb->prefix . 'babarida_reviews';
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT r.review_id, r.rating, r.title, r.review_text, r.photo_url, 
                        r.country_code, r.country_name, c.first_name, c.last_name
                 FROM {$table} r
                 LEFT JOIN {$wpdb->prefix}babarida_customers c ON r.customer_id = c.customer_id
                 WHERE r.status = 'approved' AND r.is_featured = 1
                 ORDER BY r.created_at DESC
                 LIMIT %d",
                $limit
            ),
            OBJECT
        );

        if (!$results) {
            return array();
        }

        foreach ($results as $row) {
            $row->full_name = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? ''));
        }

        return $results;
    }

    /**
     * Get approved partners from custom table
     */
    public static function get_approved_partners(): array
    {
        global $wpdb;
        $table   = $wpdb->prefix . 'babarida_partners';
        $results = $wpdb->get_results(
            "SELECT * FROM {$table} WHERE status = 'approved' ORDER BY company_name ASC",
            OBJECT
        );

        return $results ?: array();
    }

    /**
     * Generate share URLs
     */
    public static function get_share_url(string $platform): string
    {
        $url   = rawurlencode(get_permalink());
        $title = rawurlencode(get_the_title());

        return match ($platform) {
            'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
            'twitter'   => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
            'whatsapp'  => 'https://wa.me/?text=' . $title . '%20' . $url,
            'linkedin'  => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title,
            default     => '#',
        };
    }

    /**
     * Calculate reading time
     */
    public static function get_reading_time(): string
    {
        $content = get_post_field('post_content', get_the_ID());
        $words   = str_word_count(strip_tags($content));
        $minutes = max(1, (int) ceil($words / 200));

        return sprintf(
            _n('%d min read', '%d min read', $minutes, 'babarida-dive'),
            $minutes
        );
    }

    /**
     * Get liveaboard cabins
     */
    public static function get_liveaboard_cabins(int $liveaboard_id): array
    {
        global $wpdb;
        $table   = $wpdb->prefix . 'babarida_cabins';
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$table} WHERE liveaboard_id = %d AND status = 'active' ORDER BY sort_order ASC",
                $liveaboard_id
            ),
            OBJECT
        );

        return $results ?: array();
    }

    /**
     * Generate QR Code data URI
     */
    public static function generate_qr_data_uri(string $data, int $size = 200): string
    {
        if (!class_exists('Babarida_QR_Code')) {
            // Fallback: use Google Charts API as data URI is complex without a library
            $url = 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . rawurlencode($data);
            return $url;
        }
        return '';
    }
}

/* ── Global Wrappers ─────────────────────── */

function babarida_share_url(string $platform): string
{
    return Babarida_Helpers::get_share_url($platform);
}

function babarida_reading_time(): string
{
    return Babarida_Helpers::get_reading_time();
}
