<?php
/**
 * AJAX Handler Class
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

namespace Babarida_Theme;

if (!defined('ABSPATH')) {
    exit;
}

class Babarida_Ajax
{

    public function __construct()
    {
        // Newsletter Subscription (Public)
        add_action('wp_ajax_babarida_newsletter_subscribe', [$this, 'newsletter_subscribe']);
        add_action('wp_ajax_nopriv_babarida_newsletter_subscribe', [$this, 'newsletter_subscribe']);

        // Weather Data (Public)
        add_action('wp_ajax_babarida_get_weather', [$this, 'get_weather']);
        add_action('wp_ajax_nopriv_babarida_get_weather', [$this, 'get_weather']);

        // Load More Destinations (Public)
        add_action('wp_ajax_babarida_load_more', [$this, 'load_more_posts']);
        add_action('wp_ajax_nopriv_babarida_load_more', [$this, 'load_more_posts']);
    }

    /**
     * Handle Newsletter Subscription
     */
    public function newsletter_subscribe(): void
    {
        if (!isset($_POST['newsletter_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['newsletter_nonce'])), 'babarida_newsletter_subscribe')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'babarida-dive')), 403);
        }

        $email      = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        $first_name = isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';

        if (empty($email) || !is_email($email)) {
            wp_send_json_error(array('message' => __('Please enter a valid email address.', 'babarida-dive')), 400);
        }

        global $wpdb;
        $table = $wpdb->prefix . 'babarida_newsletter_subscribers';

        // Check if already exists
        $exists = $wpdb->get_var($wpdb->prepare("SELECT sub_id FROM {$table} WHERE email = %s", $email));

        if ($exists) {
            // Reactivate if unsubscribed
            $wpdb->update(
                $table,
                array(
                    'status'         => 'active',
                    'unsubscribed_at' => null,
                    'confirmed_at'   => current_time('mysql'),
                ),
                array('sub_id' => $exists),
                array('%s', '%s', '%s'),
                array('%d')
            );
            wp_send_json_success(array('message' => __('Welcome back! Your subscription has been reactivated.', 'babarida-dive')));
        }

        $inserted = $wpdb->insert(
            $table,
            array(
                'email'         => $email,
                'first_name'    => $first_name,
                'status'        => 'active',
                'source'        => 'website',
                'subscribed_at' => current_time('mysql'),
                'confirmed_at'  => current_time('mysql'),
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s')
        );

        if ($inserted) {
            wp_send_json_success(array('message' => __('Successfully subscribed! Thank you.', 'babarida-dive')));
        } else {
            wp_send_json_error(array('message' => __('An error occurred. Please try again.', 'babarida-dive')), 500);
        }
    }

    /**
     * Handle Weather Data Request
     */
    public function get_weather(): void
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'babarida_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed.'), 403);
        }

        $weather = new Babarida_Weather_Widget();
        $data    = $weather->get_cached_weather('manado');

        if ($data) {
            wp_send_json_success($data);
        } else {
            wp_send_json_error(array('message' => __('Could not fetch weather data.', 'babarida-dive')), 503);
        }
    }

    /**
     * Handle Load More Posts
     */
    public function load_more_posts(): void
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'babarida_nonce')) {
            wp_send_json_error('Security check failed.', 403);
        }

        $post_type = isset($_POST['post_type']) ? sanitize_text_field(wp_unslash($_POST['post_type'])) : 'post';
        $page      = isset($_POST['page']) ? absint($_POST['page']) : 1;
        $per_page  = isset($_POST['per_page']) ? absint($_POST['per_page']) : 6;

        $args = array(
            'post_type'      => $post_type,
            'posts_per_page' => $per_page,
            'paged'          => $page,
            'post_status'    => 'publish',
        );

        $query = new \WP_Query($args);

        if (!$query->have_posts()) {
            wp_send_json_success(array('html' => '', 'found' => 0));
        }

        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', $post_type);
        }
        wp_reset_postdata();

        wp_send_json_success(array(
            'html'  => ob_get_clean(),
            'found' => $query->found_posts,
            'max'   => $query->max_num_pages,
        ));
    }
}
