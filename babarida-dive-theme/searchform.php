<?php
/**
 * Babarida Dive Center - Search Form Template
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-field-<?php echo esc_attr(uniqid()); ?>" class="screen-reader-text"><?php esc_html_e('Search for:', 'babarida-dive'); ?></label>
    <div class="search-form-inner">
        <input type="search" id="search-field-<?php echo esc_attr(uniqid()); ?>" class="search-field" placeholder="<?php esc_attr_e('Search destinations, liveaboards...', 'babarida-dive'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" required autocomplete="off">
        <button type="submit" class="search-submit" aria-label="<?php esc_attr_e('Search', 'babarida-dive'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </button>
    </div>
</form>
