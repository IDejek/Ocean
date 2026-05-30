<?php
/**
 * Custom Navigation Walker
 * Mega Menu with Animated Underline
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

class Babarida_Nav_Walker extends \Walker_Nav_Menu
{

    private int $depth_counter = 0;

    public function start_lvl(&$output, int $depth = 0, array $args = array()): void
    {
        $this->depth_counter++;
        $classes = array('sub-menu');

        if ($depth === 0) {
            $classes[] = 'mega-menu-panel';
            $output .= "\n<div class=\"mega-menu-wrapper\" role=\"menu\">\n";
            $output .= "<div class=\"container\"><div class=\"mega-menu-inner\">\n";
            $output .= "<ul class=\"" . esc_attr(implode(' ', $classes)) . "\" role=\"menubar\">\n";
        } else {
            $output .= "\n<ul class=\"" . esc_attr(implode(' ', $classes)) . "\" role=\"menu\">\n";
        }
    }

    public function end_lvl(&$output, int $depth = 0, array $args = array()): void
    {
        $output .= "</ul>\n";
        if ($depth === 0) {
            $output .= "</div></div></div>\n";
        }
    }

    public function start_el(&$output, $item, int $depth = 0, array $args = array(), int $id = 0): void
    {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if (in_array('menu-item-has-children', $classes, true)) {
            $classes[] = 'has-submenu';
        }

        $class_names = implode(' ', array_filter($classes));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= '<li' . $class_names . ' role="none">';

        $attributes  = '';
        $link_classes = array('nav-link');

        if (in_array('current-menu-item', $classes, true) || in_array('current_page_item', $classes, true)) {
            $link_classes[] = 'active';
        }

        if (in_array('menu-item-has-children', $classes, true) && $depth === 0) {
            $link_classes[] = 'has-underline';
            $attributes .= ' aria-haspopup="true" aria-expanded="false"';
        }

        $attributes .= ' class="' . esc_attr(implode(' ', $link_classes)) . '"';

        $atts = array();
        $atts['href']   = !empty($item->url) ? $item->url : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';

        if (in_array('nav-checkin-btn', $classes, true)) {
            $link_classes[] = 'btn btn-primary btn-sm';
            $attributes = ' class="' . esc_attr(implode(' ', $link_classes)) . '"';
        }

        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value       = esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $output .= '<a' . $attributes . ' role="menuitem">';
        $output .= $title;
        if (in_array('menu-item-has-children', $classes, true) && $depth === 0) {
            $output .= ' <svg class="nav-arrow" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="6 9 12 15 18 9"/></svg>';
        }
        $output .= '</a>';
    }

    public function end_el(&$output, $item, int $depth = 0, array $args = array()): void
    {
        $output .= "</li>\n";
    }
}
