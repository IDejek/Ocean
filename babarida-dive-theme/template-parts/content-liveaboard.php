<?php
/**
 * Template Part: Liveaboard Card
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

 $lb_id      = get_the_ID();
 $length     = get_post_meta($lb_id, '_babarida_liveaboard_length', true);
 $guests     = get_post_meta($lb_id, '_babarida_liveaboard_guests', true);
 $price_from = get_post_meta($lb_id, '_babarida_liveaboard_price_from', true);
 $cabins     = get_post_meta($lb_id, '_babarida_liveaboard_cabins', true);
 $thumbnail  = get_the_post_thumbnail_url($lb_id, 'liveaboard-card') ?: BABARIDA_ASSETS . '/images/liveaboard-default.jpg';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('liveaboard-card'); ?> data-animate="fade-up">
    <a href="<?php the_permalink(); ?>" class="liveaboard-card-link">
        <div class="liveaboard-card-image">
            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" width="600" height="400">
            <div class="liveaboard-card-overlay"></div>
        </div>
        <div class="liveaboard-card-body">
            <h3 class="liveaboard-card-title"><?php the_title(); ?></h3>
            <div class="liveaboard-card-specs">
                <?php if ($length) : ?>
                    <span class="spec-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <?php echo esc_html($length); ?>
                    </span>
                <?php endif; ?>
                <?php if ($guests) : ?>
                    <span class="spec-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        <?php echo esc_html($guests); ?>
                    </span>
                <?php endif; ?>
                <?php if ($cabins) : ?>
                    <span class="spec-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/></svg>
                        <?php echo esc_html($cabins); ?>
                    </span>
                <?php endif; ?>
            </div>
            <?php if ($price_from) : ?>
                <div class="liveaboard-card-price">
                    <span class="price-from"><?php esc_html_e('From', 'babarida-dive'); ?></span>
                    <span class="price-amount"><?php echo esc_html($price_from); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </a>
</article>
