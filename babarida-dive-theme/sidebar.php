<?php
/**
 * Babarida Dive Center - Sidebar Template
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

if (!is_active_sidebar('sidebar-blog')) {
    return;
}
?>

<aside class="sidebar" role="complementary" aria-label="<?php esc_attr_e('Sidebar', 'babarida-dive'); ?>">
    <?php dynamic_sidebar('sidebar-blog'); ?>
</aside>
