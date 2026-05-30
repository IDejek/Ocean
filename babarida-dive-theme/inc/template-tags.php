<?php
/**
 * Template Tags
 * Global functions used across templates
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display Breadcrumb Navigation with Schema.org markup
 */
function babarida_breadcrumb(): void
{
    if (is_front_page()) {
        return;
    }

    $items = array(
        array(
            'label' => __('Home', 'babarida-dive'),
            'url'   => home_url('/'),
        ),
    );

    if (is_singular('post')) {
        $items[] = array(
            'label' => single_cat_title('', false) ?: __('Blog', 'babarida-dive'),
            'url'   => get_permalink(get_option('page_for_posts')),
        );
        $items[] = array('label' => get_the_title());
    } elseif (is_singular()) {
        $post_type = get_post_type_object(get_post_type());
        if ($post_type && $post_type->has_archive) {
            $items[] = array(
                'label' => $post_type->labels->name,
                'url'   => get_post_type_archive_link(get_post_type()),
            );
        }
        $items[] = array('label' => get_the_title());
    } elseif (is_category()) {
        $items[] = array('label' => single_cat_title('', false));
    } elseif (is_tag()) {
        $items[] = array('label' => single_tag_title('', false));
    } elseif (is_author()) {
        $items[] = array('label' => get_the_author());
    } elseif (is_post_type_archive()) {
        $items[] = array('label' => post_type_archive_title('', false));
    } elseif (is_404()) {
        $items[] = array('label' => __('Page Not Found', 'babarida-dive'));
    } elseif (is_search()) {
        $items[] = array('label' => sprintf(__('Search: %s', 'babarida-dive'), get_search_query()));
    }

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => array(),
    );

    $count = 0;
    echo '<ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">';
    foreach ($items as $item) {
        $count++;
        $is_last = ($count === count($items));

        $schema['itemListElement'][] = array(
            '@type'    => 'ListItem',
            'position' => $count,
            'name'     => $item['label'],
            'item'     => isset($item['url']) ? $item['url'] : get_permalink(),
        );

        echo '<li class="breadcrumb-item' . ($is_last ? ' active' : '') . '" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        if (!$is_last && isset($item['url'])) {
            echo '<a href="' . esc_url($item['url']) . '" itemprop="item"><span itemprop="name">' . esc_html($item['label']) . '</span></a>';
        } else {
            echo '<span itemprop="name" aria-current="page">' . esc_html($item['label']) . '</span>';
        }
        echo '<meta itemprop="position" content="' . esc_attr($count) . '">';
        echo '</li>';
    }
    echo '</ol>';

    // Output JSON-LD Schema
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
}

/**
 * Display Post Thumbnail with Lazy Loading
 */
function babarida_post_thumbnail(string $size = 'blog-card', array $attrs = array()): void
{
    if (!has_post_thumbnail()) {
        return;
    }

    $default_attrs = array(
        'class'   => 'post-thumbnail',
        'loading' => 'lazy',
        'width'   => 800,
        'height'  => 500,
    );

    $attrs = wp_parse_args($attrs, $default_attrs);

    the_post_thumbnail($size, $attrs);
}

/**
 * Get currency symbol
 */
function babarida_currency_symbol(string $currency = 'USD'): string
{
    $symbols = array(
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'IDR' => 'Rp',
        'SGD' => 'S$',
        'AUD' => 'A$',
    );

    return $symbols[$currency] ?? $currency;
}

/**
 * Format price
 */
function babarida_format_price(float $amount, string $currency = 'USD'): string
{
    $symbol = babarida_currency_symbol($currency);
    
    if ($currency === 'IDR') {
        return $symbol . ' ' . number_format($amount, 0, ',', '.');
    }

    return $symbol . number_format($amount, 2, '.', ',');
}

/**
 * Display status badge
 */
function babarida_status_badge(string $status): void
{
    $classes = array(
        'pending'    => 'status-pending',
        'confirmed'  => 'status-confirmed',
        'paid'       => 'status-paid',
        'checked_in' => 'status-checked-in',
        'completed'  => 'status-completed',
        'cancelled'  => 'status-cancelled',
        'refunded'   => 'status-refunded',
    );

    $labels = array(
        'pending'    => __('Pending', 'babarida-dive'),
        'confirmed'  => __('Confirmed', 'babarida-dive'),
        'paid'       => __('Paid', 'babarida-dive'),
        'checked_in' => __('Checked In', 'babarida-dive'),
        'completed'  => __('Completed', 'babarida-dive'),
        'cancelled'  => __('Cancelled', 'babarida-dive'),
        'refunded'   => __('Refunded', 'babarida-dive'),
    );

    $class = $classes[$status] ?? 'status-default';
    $label = $labels[$status] ?? ucfirst($status);

    printf(
        '<span class="status-badge %s">%s</span>',
        esc_attr($class),
        esc_html($label)
    );
}

/**
 * Truncate text
 */
function babarida_truncate(string $text, int $length = 100, string $ending = '...'): string
{
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . $ending;
}
