<?php
/**
 * Babarida Dive Center - Theme Functions
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

define('BABARIDA_VERSION', '7.0.0');
define('BABARIDA_DIR', get_template_directory());
define('BABARIDA_URI', get_template_directory_uri());
define('BABARIDA_INC', BABARIDA_DIR . '/inc');
define('BABARIDA_ASSETS', BABARIDA_URI . '/assets');

/* ─────────────────────────────────────────────
   REQUIRE CORE FILES
   ───────────────────────────────────────────── */

require_once BABARIDA_INC . '/class-babarida-setup.php';
require_once BABARIDA_INC . '/class-babarida-assets.php';
require_once BABARIDA_INC . '/class-babarida-nav-walker.php';
require_once BABARIDA_INC . '/class-babarida-mobile-nav-walker.php';
require_once BABARIDA_INC . '/class-babarida-cpt.php';
require_once BABARIDA_INC . '/class-babarida-customizer.php';
require_once BABARIDA_INC . '/class-babarida-icons.php';
require_once BABARIDA_INC . '/class-babarida-helpers.php';
require_once BABARIDA_INC . '/class-babarida-ajax.php';
require_once BABARIDA_INC . '/class-babarida-weather-widget.php';
require_once BABARIDA_INC . '/class-babarida-seo.php';
require_once BABARIDA_INC . '/template-tags.php';

/* ─────────────────────────────────────────────
   INITIALIZE CLASSES
   ───────────────────────────────────────────── */

new Babarida_Setup();
new Babarida_Assets();
new Babarida_CPT();
new Babarida_Customizer();
new Babarida_Ajax();
new Babarida_Weather_Widget();
new Babarida_SEO();

/* ─────────────────────────────────────────────
   IMAGE SIZES
   ───────────────────────────────────────────── */

add_action('after_setup_theme', 'babarida_image_sizes');
function babarida_image_sizes(): void
{
    add_image_size('hero-full', 1920, 1080, true);
    add_image_size('destinations-card', 600, 400, true);
    add_image_size('liveaboard-card', 600, 400, true);
    add_image_size('gallery-masonry', 600, 600, false);
    add_image_size('gallery-thumb', 300, 300, true);
    add_image_size('testimonial-avatar', 112, 112, true);
    add_image_size('partner-logo', 320, 160, true);
    add_image_size('cabin-gallery', 800, 600, true);
    add_image_size('blog-card', 800, 500, true);
    add_image_size('og-image', 1200, 630, true);
}

/* ─────────────────────────────────────────────
   WIDGET AREAS
   ───────────────────────────────────────────── */

add_action('widgets_init', 'babarida_widgets_init');
function babarida_widgets_init(): void
{
    register_sidebar(
        array(
            'name'          => __('Blog Sidebar', 'babarida-dive'),
            'id'            => 'sidebar-blog',
            'description'   => __('Appears on blog and archive pages.', 'babarida-dive'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );

    register_sidebar(
        array(
            'name'          => __('Footer Column 1', 'babarida-dive'),
            'id'            => 'footer-1',
            'description'   => __('First footer widget area.', 'babarida-dive'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );

    register_sidebar(
        array(
            'name'          => __('Footer Column 2', 'babarida-dive'),
            'id'            => 'footer-2',
            'description'   => __('Second footer widget area.', 'babarida-dive'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
}

/* ─────────────────────────────────────────────
   WP_HEAD CLEANUP
   ───────────────────────────────────────────── */

add_action('init', 'babarida_head_cleanup');
function babarida_head_cleanup(): void
{
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
}

/* ─────────────────────────────────────────────
   DISABLE XML-RPC
   ───────────────────────────────────────────── */

add_filter('xmlrpc_enabled', '__return_false');

/* ─────────────────────────────────────────────
   REMOVE WP VERSION FROM SCRIPTS/STYLES
   ───────────────────────────────────────────── */

add_filter('style_loader_src', 'babarida_remove_version', 9999);
add_filter('script_loader_src', 'babarida_remove_version', 9999);
function babarida_remove_version(string $src): string
{
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

/* ─────────────────────────────────────────────
   SECURITY HEADERS
   ───────────────────────────────────────────── */

add_action('send_headers', 'babarida_security_headers');
function babarida_security_headers(): void
{
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Permissions-Policy: geolocation=(self), camera=(), microphone=()");
    }
}

/* ─────────────────────────────────────────────
   LOGIN PAGE BRANDING
   ───────────────────────────────────────────── */

add_action('login_enqueue_scripts', 'babarida_login_styles');
function babarida_login_styles(): void
{
    wp_enqueue_style(
        'babarida-login',
        BABARIDA_URI . '/assets/css/login.css',
        array(),
        BABARIDA_VERSION
    );
}

add_filter('login_headerurl', function (): string {
    return home_url('/');
});

add_filter('login_headertext', function (): string {
    return 'Babarida Dive Center';
});

/* ─────────────────────────────────────────────
   GRAVITY FORMS (if installed) - disable CSS
   ───────────────────────────────────────────── */

add_filter('gform_enqueue_scripts', 'babarida_gform_dequeue_css', 10, 2);
function babarida_gform_dequeue_css(array $scripts, array $form): array
{
    if (wp_style_is('gforms_formsmain_css', 'enqueued')) {
        wp_dequeue_style('gforms_formsmain_css');
    }
    if (wp_style_is('gforms_reset_css', 'enqueued')) {
        wp_dequeue_style('gforms_reset_css');
    }
    if (wp_style_is('gforms_ready_class_css', 'enqueued')) {
        wp_dequeue_style('gforms_ready_class_css');
    }
    if (wp_style_is('gforms_browsers_css', 'enqueued')) {
        wp_dequeue_style('gforms_browsers_css');
    }
    return $scripts;
}

/* ─────────────────────────────────────────────
   EXCERPT LENGTH
   ───────────────────────────────────────────── */

add_filter('excerpt_length', 'babarida_excerpt_length');
function babarida_excerpt_length(int $length): int
{
    return 25;
}

add_filter('excerpt_more', 'babarida_excerpt_more');
function babarida_excerpt_more(): string
{
    return '...';
}

/* ─────────────────────────────────────────────
   BODY CLASSES
   ───────────────────────────────────────────── */

add_filter('body_class', 'babarida_body_classes');
function babarida_body_classes(array $classes): array
{
    if (is_front_page()) {
        $classes[] = 'front-page';
    }
    if (is_singular('destination')) {
        $classes[] = 'destination-page';
    }
    if (is_singular('liveaboard')) {
        $classes[] = 'liveaboard-page';
    }
    if (is_post_type_archive('liveaboard')) {
        $classes[] = 'liveaboard-archive';
    }
    if (is_post_type_archive('destination')) {
        $classes[] = 'destination-archive';
    }
    $classes[] = 'no-js';
    return $classes;
}

/* ─────────────────────────────────────────────
   REMOVE NO-JS ON DOM READY (via inline script)
   ───────────────────────────────────────────── */

add_action('wp_head', 'babarida_remove_nojs', 1);
function babarida_remove_nojs(): void
{
    echo "<script>document.documentElement.classList.remove('no-js');</script>\n";
}

/* ─────────────────────────────────────────────
   PINGBACK HEADER
   ───────────────────────────────────────────── */

add_action('wp_head', 'babarida_pingback_header');
function babarida_pingback_header(): void
{
    if (is_singular() && pings_open()) {
        printf(
            '<link rel="pingback" href="%s">' . "\n",
            esc_url(get_bloginfo('pingback_url'))
        );
    }
}
