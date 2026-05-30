<?php
/**
 * Mobile Navigation Walker
 * Accordion-style mobile menu
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

class Babarida_Mobile_Nav_Walker extends \Walker_Nav_Menu
{

    public function start_lvl(&$output, int $depth = 0, array $args = array()): void
    {
        $indent  = str_repeat("\t", $depth);
        $output .= "\n{$indent}<div class=\"mobile-submenu\" role=\"menu\" aria-hidden=\"true\">\n{$indent}<ul class=\"mobile-sub-menu\">\n";
    }

    public function end_lvl(&$output, int $depth = 0, array $args = array()): void
    {
        $indent  = str_repeat("\t", $depth);
        $output .= "{$indent}</ul>\n{$indent}</div>\n";
    }

    public function start_el(&$output, $item, int $depth = 0, array $args = array(), int $id = 0): void
    {
        $classes     = empty($item->classes) ? array() : (array) $item->classes;
        $classes[]   = 'menu-item-' . $item->ID;
        $class_names = implode(' ', array_filter($classes));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= '<li' . $class_names . ' role="none">';

        $atts = array(
            'href'   => !empty($item->url) ? $item->url : '',
            'target' => !empty($item->target) ? $item->target : '',
            'rel'    => !empty($item->xfn) ? $item->xfn : '',
        );

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value       = esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $link_class = 'mobile-nav-link';
        if (in_array('current-menu-item', $classes, true)) {
            $link_class .= ' active';
        }
        $attributes .= ' class="' . esc_attr($link_class) . '" role="menuitem"';

        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $output .= '<a' . $attributes . '>';
        $output .= $title;

        if (in_array('menu-item-has-children', $classes, true)) {
            $output .= ' <button class="mobile-submenu-toggle" aria-label="' . esc_attr(sprintf(__('Toggle %s submenu', 'babarida-dive'), $title)) . '" aria-expanded="false">';
            $output .= '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>';
            $output .= '</button>';
        }

        $output .= '</a>';
    }

    public function end_el(&$output, $item, int $depth = 0, array $args = array()): void
    {
        $output .= "</li>\n";
    }
}
