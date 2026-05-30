<?php
/**
 * SEO & Schema.org Integration
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

namespace Babarida_Theme;

if (!defined('ABSPATH')) {
    exit;
}

class Babarida_SEO
{

    public function __construct()
    {
        add_action('wp_head', [$this, 'output_meta_tags'], 5);
        add_action('wp_head', [$this, 'output_verification_meta'], 1);
        add_action('wp_head', [$this, 'output_local_business_schema'], 99);
        add_action('template_redirect', [$this, 'output_robots_meta']);
    }

    /**
     * Output custom SEO meta tags
     */
    public function output_meta_tags(): void
    {
        if (is_admin()) {
            return;
        }

        global $wpdb;
        $post_id = get_the_ID();

        if (!$post_id) {
            return;
        }

        $table = $wpdb->prefix . 'babarida_seo_meta';
        $seo   = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table} WHERE post_id = %d", $post_id), OBJECT);

        if (!$seo) {
            return;
        }

        // Meta Title
        if (!empty($seo->meta_title)) {
            echo '<title>' . esc_html($seo->meta_title) . '</title>' . "\n";
        }

        // Meta Description
        if (!empty($seo->meta_description)) {
            echo '<meta name="description" content="' . esc_attr($seo->meta_description) . '">' . "\n";
        }

        // Open Graph
        if (!empty($seo->og_title)) {
            echo '<meta property="og:title" content="' . esc_attr($seo->og_title) . '">' . "\n";
        }
        if (!empty($seo->og_description)) {
            echo '<meta property="og:description" content="' . esc_attr($seo->og_description) . '">' . "\n";
        }
        if (!empty($seo->og_image)) {
            echo '<meta property="og:image" content="' . esc_url($seo->og_image) . '">' . "\n";
        }
        if (!empty($seo->og_type)) {
            echo '<meta property="og:type" content="' . esc_attr($seo->og_type) . '">' . "\n";
        }

        // Twitter Cards
        if (!empty($seo->twitter_card)) {
            echo '<meta name="twitter:card" content="' . esc_attr($seo->twitter_card) . '">' . "\n";
        }
        if (!empty($seo->twitter_title)) {
            echo '<meta name="twitter:title" content="' . esc_attr($seo->twitter_title) . '">' . "\n";
        }
        if (!empty($seo->twitter_description)) {
            echo '<meta name="twitter:description" content="' . esc_attr($seo->twitter_description) . '">' . "\n";
        }
        if (!empty($seo->twitter_image)) {
            echo '<meta name="twitter:image" content="' . esc_url($seo->twitter_image) . '">' . "\n";
        }

        // Canonical URL
        if (!empty($seo->canonical_url)) {
            echo '<link rel="canonical" href="' . esc_url($seo->canonical_url) . '">' . "\n";
        }

        // Robots
        if ($seo->noindex) {
            echo '<meta name="robots" content="noindex">' . "\n";
        }
        if ($seo->nofollow) {
            echo '<meta name="robots" content="nofollow">' . "\n";
        }

        // Custom Schema
        if (!empty($seo->schema_json)) {
            echo '<script type="application/ld+json">' . wp_json_encode(json_decode($seo->schema_json, true), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
        }
    }

    /**
     * Output Search Console Verification Meta
     */
    public function output_verification_meta(): void
    {
        $google = get_theme_mod('babarida_google_site_verification', '');
        $bing   = get_theme_mod('babarida_bing_site_verification', '');

        if (!empty($google)) {
            echo '<meta name="google-site-verification" content="' . esc_attr($google) . '">' . "\n";
        }
        if (!empty($bing)) {
            echo '<meta name="msvalidate.01" content="' . esc_attr($bing) . '">' . "\n";
        }
    }

    /**
     * Output LocalBusiness Schema for homepage
     */
    public function output_local_business_schema(): void
    {
        if (!is_front_page()) {
            return;
        }

        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'TouristInformationCenter',
            'name'     => 'Babarida Dive Center',
            'url'      => home_url('/'),
            'logo'     => get_template_directory_uri() . '/assets/images/babarida-logo-white.svg',
            'image'    => get_template_directory_uri() . '/assets/images/hero-bunaken-reef.jpg',
            'description' => 'Premium diving experiences in the heart of North Sulawesi\'s Coral Triangle. Bunaken, Siladen, Bangka, and Lembeh.',
            'telephone' => '+62 895 8019 60359',
            'email'    => 'info@babaridadive.com',
            'address'  => array(
                '@type'           => 'PostalAddress',
                'streetAddress'   => 'Bunaken',
                'addressLocality' => 'Manado',
                'addressRegion'   => 'North Sulawesi',
                'postalCode'     => '95122',
                'addressCountry'  => 'ID',
            ),
            'geo' => array(
                '@type'     => 'GeoCoordinates',
                'latitude'  => 1.4748,
                'longitude' => 124.8421,
            ),
            'openingHoursSpecification' => array(
                array(
                    '@type'     => 'OpeningHoursSpecification',
                    'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
                    'opens'     => '07:00',
                    'closes'    => '20:00',
                ),
            ),
            'priceRange'     => '$$',
            'sameAs'         => array(
                'https://www.instagram.com/babaridadive/',
                'https://www.facebook.com/babaridadive/',
                'https://www.tiktok.com/@babaridadive/',
                'https://www.youtube.com/@babaridadive/',
            ),
            'hasOfferCatalog' => array(
                '@type' => 'OfferCatalog',
                'name'  => 'Dive Services',
                'itemListElement' => array(
                    array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'Liveaboard Cruises')),
                    array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'Dive Safaris')),
                    array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'Water Sports')),
                    array('@type' => 'Offer', 'itemOffered' => array('@type' => 'Service', 'name' => 'SSI Courses')),
                ),
            ),
        );

        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }

    /**
     * Handle noindex for specific pages
     */
    public function output_robots_meta(): void
    {
        if (is_paged()) {
            // Optionally noindex paginated archives, but we allow it for now
        }
    }
}
