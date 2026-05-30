<?php
/**
 * Template Part: No Content Found
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="no-results not-found" data-animate="fade-up">
    <div class="no-results-inner">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <h2 class="no-results-title"><?php esc_html_e('Nothing Found', 'babarida-dive'); ?></h2>
        <p class="no-results-text"><?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'babarida-dive'); ?></p>
        <?php get_search_form(); ?>
    </div>
</section>
