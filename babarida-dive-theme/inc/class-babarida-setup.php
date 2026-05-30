<?php
/**
 * Theme Setup Class
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

namespace Babarida_Theme;

if (!defined('ABSPATH')) {
    exit;
}

class Babarida_Setup
{

    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'theme_setup']);
        add_action('after_setup_theme', [$this, 'register_menus']);
    }

    public function theme_setup(): void
    {
        load_theme_textdomain('babarida-dive', BABARIDA_DIR . '/languages');

        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets'));
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('responsive-embeds');
        add_theme_support('wp-block-styles');
        add_theme_support('align-wide');
        add_theme_support('custom-logo', array(
            'height'      => 60,
            'width'       => 200,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array('site-title', 'site-description'),
        ));
        add_theme_support('custom-background', array(
            'default-color' => '0F172A',
        ));

        add_editor_style('assets/css/editor.css');
    }

    public function register_menus(): void
    {
        register_nav_menus(array(
            'primary' => __('Primary Navigation', 'babarida-dive'),
            'mobile'  => __('Mobile Navigation', 'babarida-dive'),
            'footer'  => __('Footer Navigation', 'babarida-dive'),
        ));
    }
}
