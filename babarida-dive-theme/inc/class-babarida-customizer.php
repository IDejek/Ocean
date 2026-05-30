<?php
/**
 * Theme Customizer Class
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

namespace Babarida_Theme;

if (!defined('ABSPATH')) {
    exit;
}

class Babarida_Customizer
{

    public function __construct()
    {
        add_action('customize_register', [$this, 'register_customizer']);
    }

    public function register_customizer(\WP_Customize_Manager $wp_customize): void
    {
        /* ── Hero Section ───────────────────── */
        $wp_customize->add_section('babarida_hero_section', array(
            'title'       => __('Hero Section', 'babarida-dive'),
            'description' => __('Configure the fullscreen cinematic hero section on the homepage.', 'babarida-dive'),
            'priority'    => 30,
        ));

        $wp_customize->add_setting('babarida_hero_type', array(
            'default'           => 'image',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('babarida_hero_type', array(
            'label'   => __('Hero Type', 'babarida-dive'),
            'section' => 'babarida_hero_section',
            'type'    => 'select',
            'choices' => array(
                'image' => __('Image', 'babarida-dive'),
                'video' => __('Video', 'babarida-dive'),
            ),
        ));

        $wp_customize->add_setting('babarida_hero_image_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new \WP_Customize_Image_Control($wp_customize, 'babarida_hero_image_url', array(
            'label'   => __('Hero Image', 'babarida-dive'),
            'section' => 'babarida_hero_section',
        )));

        $wp_customize->add_setting('babarida_hero_video_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control('babarida_hero_video_url', array(
            'label'       => __('Hero Video URL (MP4)', 'babarida-dive'),
            'section'     => 'babarida_hero_section',
            'type'        => 'url',
            'input_attrs' => array('placeholder' => 'https://example.com/hero-video.mp4'),
        ));

        $wp_customize->add_setting('babarida_hero_subtitle', array(
            'default'           => 'The quality of your dive adventure depends on who guides you!',
            'sanitize_callback' => 'wp_kses_post',
        ));
        $wp_customize->add_control('babarida_hero_subtitle', array(
            'label'   => __('Hero Subtitle', 'babarida-dive'),
            'section' => 'babarida_hero_section',
            'type'    => 'textarea',
        ));

        $wp_customize->add_setting('babarida_hero_cta_text', array(
            'default'           => 'Book Your Dive',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('babarida_hero_cta_text', array(
            'label'   => __('CTA Button Text', 'babarida-dive'),
            'section' => 'babarida_hero_section',
            'type'    => 'text',
        ));

        $wp_customize->add_setting('babarida_hero_cta_url', array(
            'default'           => '/check-in/',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control('babarida_hero_cta_url', array(
            'label'   => __('CTA Button URL', 'babarida-dive'),
            'section' => 'babarida_hero_section',
            'type'    => 'url',
        ));

        /* ── Contact Information ─────────────── */
        $wp_customize->add_section('babarida_contact_section', array(
            'title'    => __('Contact Information', 'babarida-dive'),
            'priority' => 40,
        ));

        $wp_customize->add_setting('babarida_whatsapp_number', array(
            'default'           => '62895801960359',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('babarida_whatsapp_number', array(
            'label'       => __('WhatsApp Number (with country code, no +)', 'babarida-dive'),
            'section'     => 'babarida_contact_section',
            'type'        => 'text',
            'input_attrs' => array('placeholder' => '6281234567890'),
        ));

        $wp_customize->add_setting('babarida_email_address', array(
            'default'           => 'info@babaridadive.com',
            'sanitize_callback' => 'sanitize_email',
        ));
        $wp_customize->add_control('babarida_email_address', array(
            'label'   => __('Email Address', 'babarida-dive'),
            'section' => 'babarida_contact_section',
            'type'    => 'email',
        ));

        $wp_customize->add_setting('babarida_phone_number', array(
            'default'           => '+62 895 8019 60359',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('babarida_phone_number', array(
            'label'   => __('Phone Number', 'babarida-dive'),
            'section' => 'babarida_contact_section',
            'type'    => 'text',
        ));

        /* ── Social Media ────────────────────── */
        $wp_customize->add_section('babarida_social_section', array(
            'title'    => __('Social Media Links', 'babarida-dive'),
            'priority' => 45,
        ));

        $socials = array('instagram', 'facebook', 'tiktok', 'youtube');
        foreach ($socials as $social) {
            $wp_customize->add_setting('babarida_' . $social . '_url', array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            ));
            $wp_customize->add_control('babarida_' . $social . '_url', array(
                'label'   => sprintf(__('%s URL', 'babarida-dive'), ucfirst($social)),
                'section' => 'babarida_social_section',
                'type'    => 'url',
            ));
        }

        /* ── SEO & Verification ──────────────── */
        $wp_customize->add_section('babarida_seo_section', array(
            'title'    => __('SEO & Search Console', 'babarida-dive'),
            'priority' => 50,
        ));

        $wp_customize->add_setting('babarida_google_site_verification', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('babarida_google_site_verification', array(
            'label'   => __('Google Search Console HTML Verification', 'babarida-dive'),
            'section' => 'babarida_seo_section',
            'type'    => 'text',
        ));

        $wp_customize->add_setting('babarida_bing_site_verification', array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('babarida_bing_site_verification', array(
            'label'   => __('Bing Webmaster Tools Verification', 'babarida-dive'),
            'section' => 'babarida_seo_section',
            'type'    => 'text',
        ));

        /* ── Footer ──────────────────────────── */
        $wp_customize->add_section('babarida_footer_section', array(
            'title'    => __('Footer Settings', 'babarida-dive'),
            'priority' => 60,
        ));

        $wp_customize->add_setting('babarida_footer_text', array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
        ));
        $wp_customize->add_control('babarida_footer_text', array(
            'label'   => __('Footer Custom Text', 'babarida-dive'),
            'section' => 'babarida_footer_section',
            'type'    => 'textarea',
        ));
    }
}
