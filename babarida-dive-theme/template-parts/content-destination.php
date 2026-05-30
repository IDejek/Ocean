<?php
/**
 * Template Part: Destination Card
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

 $dest_id    = get_the_ID();
 $subtitle   = get_post_meta($dest_id, '_babarida_destination_subtitle', true);
 $dives      = get_post_meta($dest_id, '_babarida_destination_dive_sites', true);
 $difficulty = get_post_meta($dest_id, '_babarida_destination_difficulty', true);
 $thumbnail  = get_the_post_thumbnail_url($dest_id, 'destinations-card') ?: BABARIDA_ASSETS . '/images/destination-default.jpg';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('destination-card'); ?> data-animate="fade-up">
    <a href="<?php the_permalink(); ?>" class="destination-card-link" aria-label="<?php echo esc_attr(get_the_title()); ?>">
        <div class="destination-card-image">
            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" width="600" height="400">
            <div class="destination-card-overlay"></div>
            <?php if ($difficulty) : ?>
                <span class="destination-badge"><?php echo esc_html($difficulty); ?></span>
            <?php endif; ?>
        </div>
        <div class="destination-card-content">
            <h3 class="destination-card-title"><?php the_title(); ?></h3>
            <?php if ($subtitle) : ?>
                <p class="destination-card-subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
            <?php if ($dives) : ?>
                <span class="destination-card-stats"><?php echo esc_html($dives); ?> <?php esc_html_e('Dive Sites', 'babarida-dive'); ?></span>
            <?php endif; ?>
            <span class="destination-card-arrow">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </span>
        </div>
    </a>
</article>
