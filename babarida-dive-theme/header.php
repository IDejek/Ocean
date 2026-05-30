<?php
/**
 * Babarida Dive Center - Header Template
 * Top Bar + Main Header with Glassmorphism
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

 $whatsapp_number = '62895801960359';
 $whatsapp_link   = 'https://wa.me/' . $whatsapp_number;
 $email_address   = 'info@babaridadive.com';
 $current_lang    = function_exists('pll_current_language') ? pll_current_language('slug') : 'en';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#00BFFF">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class('babarida-body'); ?>>
<?php wp_body_open(); ?>

<!-- Top Bar -->
<div class="top-bar" id="top-bar" role="complementary" aria-label="<?php esc_attr_e('Top Information Bar', 'babarida-dive'); ?>">
    <div class="container top-bar-inner">
        <div class="top-bar-left">
            <a href="<?php echo esc_url(home_url('/check-in/')); ?>" class="top-bar-checkin btn-checkin" aria-label="<?php esc_attr_e('Online Check-In', 'babarida-dive'); ?>">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span><?php esc_html_e('Check-In', 'babarida-dive'); ?></span>
            </a>
        </div>

        <div class="top-bar-center">
            <div class="world-clocks" id="world-clocks" aria-label="<?php esc_attr_e('World Clocks', 'babarida-dive'); ?>">
                <?php
                $desktop_clocks = array(
                    array('city' => 'Manado',   'tz' => 'Asia/Makassar'),
                    array('city' => 'Jakarta',  'tz' => 'Asia/Jakarta'),
                    array('city' => 'Singapore', 'tz' => 'Asia/Singapore'),
                    array('city' => 'Dubai',    'tz' => 'Asia/Dubai'),
                    array('city' => 'London',   'tz' => 'Europe/London'),
                    array('city' => 'New York', 'tz' => 'America/New_York'),
                    array('city' => 'Tokyo',    'tz' => 'Asia/Tokyo'),
                    array('city' => 'Seoul',    'tz' => 'Asia/Seoul'),
                );
                $mobile_clocks = array(
                    array('city' => 'Manado',    'tz' => 'Asia/Makassar'),
                    array('city' => 'Singapore', 'tz' => 'Asia/Singapore'),
                    array('city' => 'London',    'tz' => 'Europe/London'),
                    array('city' => 'Seoul',     'tz' => 'Asia/Seoul'),
                );
                ?>
                <div class="clocks-desktop" data-clocks='<?php echo esc_attr(wp_json_encode($desktop_clocks)); ?>'>
                    <?php foreach ($desktop_clocks as $clock) : ?>
                        <div class="clock-item" data-tz="<?php echo esc_attr($clock['tz']); ?>">
                            <span class="clock-city"><?php echo esc_html($clock['city']); ?></span>
                            <span class="clock-time">--:--</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="clocks-mobile" data-clocks='<?php echo esc_attr(wp_json_encode($mobile_clocks)); ?>'>
                    <?php foreach ($mobile_clocks as $clock) : ?>
                        <div class="clock-item" data-tz="<?php echo esc_attr($clock['tz']); ?>">
                            <span class="clock-city"><?php echo esc_html($clock['city']); ?></span>
                            <span class="clock-time">--:--</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="top-bar-right">
            <a href="<?php echo esc_url($whatsapp_link); ?>" target="_blank" rel="noopener noreferrer" class="top-bar-link" aria-label="<?php esc_attr_e('Contact via WhatsApp', 'babarida-dive'); ?>">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                <span class="top-bar-link-text"><?php esc_html_e('WhatsApp', 'babarida-dive'); ?></span>
            </a>
            <a href="mailto:<?php echo esc_attr($email_address); ?>" class="top-bar-link" aria-label="<?php esc_attr_e('Send Email', 'babarida-dive'); ?>">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <span class="top-bar-link-text"><?php esc_html_e('Email', 'babarida-dive'); ?></span>
            </a>
            <?php if (function_exists('pll_the_languages')) : ?>
                <div class="top-bar-lang">
                    <?php pll_the_languages(array('dropdown' => 1, 'show_flags' => 0, 'show_names' => 1)); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="main-header" id="main-header" role="banner">
    <div class="container main-header-inner">
        <div class="header-brand">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="header-logo-link" aria-label="<?php esc_attr_e('Babarida Dive Center - Home', 'babarida-dive'); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/babarida-logo-white.svg'); ?>" alt="Babarida Dive Center" class="header-logo" width="180" height="50">
                </a>
            <?php endif; ?>
        </div>

        <nav class="header-nav" id="header-nav" role="navigation" aria-label="<?php esc_attr_e('Main Navigation', 'babarida-dive'); ?>">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'nav-menu',
                        'fallback_cb'    => false,
                        'depth'          => 3,
                        'walker'         => new Babarida_Nav_Walker(),
                    )
                );
            } else {
                echo '<ul class="nav-menu">';
                echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'babarida-dive') . '</a></li>';
                echo '<li class="menu-item-has-children"><a href="' . esc_url(home_url('/destinations/bunaken/')) . '">Bunaken</a>';
                echo '<ul class="sub-menu">';
                echo '<li><a href="' . esc_url(home_url('/destinations/bunaken/liveaboards/')) . '">' . esc_html__('Liveaboards', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/bunaken/dive-stay/')) . '">' . esc_html__('Dive & Stay', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/bunaken/snorkeling/')) . '">' . esc_html__('Snorkeling', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/bunaken/dive-safari/')) . '">' . esc_html__('Dive Safari', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/bunaken/water-sports/')) . '">' . esc_html__('Water Sports', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/bunaken/fam-island-divecamp/')) . '">' . esc_html__('Fam Island Divecamp', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/bunaken/info/')) . '">' . esc_html__('Info Bunaken', 'babarida-dive') . '</a></li>';
                echo '</ul></li>';
                echo '<li class="menu-item-has-children"><a href="' . esc_url(home_url('/destinations/siladen/')) . '">Siladen</a>';
                echo '<ul class="sub-menu">';
                echo '<li><a href="' . esc_url(home_url('/destinations/siladen/liveaboards/')) . '">' . esc_html__('Liveaboards', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/siladen/day-trip/')) . '">' . esc_html__('Day Trip', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/siladen/snorkeling/')) . '">' . esc_html__('Snorkeling', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/siladen/dive-center-courses/')) . '">' . esc_html__('Dive Center & Courses', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/siladen/info/')) . '">' . esc_html__('Siladen Info', 'babarida-dive') . '</a></li>';
                echo '</ul></li>';
                echo '<li class="menu-item-has-children"><a href="' . esc_url(home_url('/destinations/bangka/')) . '">Bangka</a>';
                echo '<ul class="sub-menu">';
                echo '<li><a href="' . esc_url(home_url('/destinations/bangka/liveaboards/')) . '">' . esc_html__('Liveaboards', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/bangka/day-trip/')) . '">' . esc_html__('Day Trip', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/bangka/snorkeling/')) . '">' . esc_html__('Snorkeling', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/bangka/dive-center-courses/')) . '">' . esc_html__('Dive Center & Courses', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/bangka/info/')) . '">' . esc_html__('Bangka Info', 'babarida-dive') . '</a></li>';
                echo '</ul></li>';
                echo '<li class="menu-item-has-children"><a href="' . esc_url(home_url('/destinations/lembeh/')) . '">Lembeh</a>';
                echo '<ul class="sub-menu">';
                echo '<li><a href="' . esc_url(home_url('/destinations/lembeh/liveaboards/')) . '">' . esc_html__('Liveaboards', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/lembeh/day-trip/')) . '">' . esc_html__('Day Trip', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/lembeh/snorkeling/')) . '">' . esc_html__('Snorkeling', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/lembeh/dive-center-courses/')) . '">' . esc_html__('Dive Center & Courses', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/destinations/lembeh/info/')) . '">' . esc_html__('Lembeh Info', 'babarida-dive') . '</a></li>';
                echo '</ul></li>';
                echo '<li class="menu-item-has-children"><a href="' . esc_url(get_post_type_archive_link('liveaboard')) . '">' . esc_html__('Liveaboards', 'babarida-dive') . '</a>';
                echo '<ul class="sub-menu">';
                echo '<li><a href="' . esc_url(get_post_type_archive_link('liveaboard')) . '">' . esc_html__('Boat Listings', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/liveaboards/expeditions/')) . '">' . esc_html__('Expeditions', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/liveaboards/charter/')) . '">' . esc_html__('Charter', 'babarida-dive') . '</a></li>';
                echo '</ul></li>';
                echo '<li class="menu-item-has-children"><a href="' . esc_url(home_url('/info/')) . '">' . esc_html__('Info', 'babarida-dive') . '</a>';
                echo '<ul class="sub-menu">';
                echo '<li><a href="' . esc_url(home_url('/blog/')) . '">' . esc_html__('Blog', 'babarida-dive') . '</a></li>';
                echo '<li><a href="' . esc_url(home_url('/faq/')) . '">' . esc_html__('FAQ', 'babarida-dive') . '</a></li>';
                echo '</ul></li>';
                echo '<li><a href="' . esc_url(home_url('/check-in/')) . '" class="nav-checkin-btn">' . esc_html__('Check-In', 'babarida-dive') . '</a></li>';
                echo '</ul>';
            }
            ?>
        </nav>

        <div class="header-actions">
            <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="<?php esc_attr_e('Toggle Menu', 'babarida-dive'); ?>" aria-expanded="false" aria-controls="mobile-menu-panel">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Panel -->
    <div class="mobile-menu-panel" id="mobile-menu-panel" role="dialog" aria-label="<?php esc_attr_e('Mobile Navigation', 'babarida-dive'); ?>" aria-hidden="true">
        <div class="mobile-menu-header">
            <span class="mobile-menu-title"><?php esc_html_e('Menu', 'babarida-dive'); ?></span>
            <button class="mobile-menu-close" id="mobile-menu-close" aria-label="<?php esc_attr_e('Close Menu', 'babarida-dive'); ?>">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="mobile-menu-body" id="mobile-menu-body">
            <?php
            if (has_nav_menu('mobile')) {
                wp_nav_menu(
                    array(
                        'theme_location' => 'mobile',
                        'container'      => false,
                        'menu_class'     => 'mobile-nav-menu',
                        'fallback_cb'    => false,
                        'depth'          => 3,
                        'walker'         => new Babarida_Mobile_Nav_Walker(),
                    )
                );
            } else {
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'mobile-nav-menu',
                        'fallback_cb'    => false,
                        'depth'          => 3,
                        'walker'         => new Babarida_Mobile_Nav_Walker(),
                    )
                );
            }
            ?>
            <div class="mobile-menu-footer">
                <a href="<?php echo esc_url($whatsapp_link); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-block">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    <?php esc_html_e('WhatsApp Us', 'babarida-dive'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/check-in/')); ?>" class="btn btn-primary btn-block">
                    <?php esc_html_e('Online Check-In', 'babarida-dive'); ?>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Floating Contact System -->
<div class="floating-contacts" id="floating-contacts" role="complementary" aria-label="<?php esc_attr_e('Quick Contact', 'babarida-dive'); ?>">
    <a href="<?php echo esc_url($whatsapp_link); ?>" target="_blank" rel="noopener noreferrer" class="floating-btn floating-whatsapp" aria-label="<?php esc_attr_e('Chat on WhatsApp', 'babarida-dive'); ?>">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        <span class="floating-btn-pulse"></span>
    </a>
    <a href="mailto:<?php echo esc_attr($email_address); ?>" class="floating-btn floating-email" aria-label="<?php esc_attr_e('Send Email', 'babarida-dive'); ?>">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
    </a>
    <a href="<?php echo esc_url(home_url('/check-in/')); ?>" class="floating-btn floating-booking" aria-label="<?php esc_attr_e('Book Now', 'babarida-dive'); ?>">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    </a>
    <button class="floating-btn floating-back-top" id="floating-back-top" aria-label="<?php esc_attr_e('Back to Top', 'babarida-dive'); ?>" style="opacity:0;visibility:hidden;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg>
    </button>
</div>
