<?php
/**
 * Template Part: Standard Post Content
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?> data-animate="fade-up">
    <a href="<?php the_permalink(); ?>" class="post-card-link" aria-label="<?php echo esc_attr(get_the_title()); ?>">
        <div class="post-card-image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('blog-card', array('loading' => 'lazy', 'class' => 'post-card-img')); ?>
            <?php else : ?>
                <div class="post-card-placeholder">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                </div>
            <?php endif; ?>
            <div class="post-card-overlay"></div>
            <div class="post-card-category">
                <?php
                $categories = get_the_category();
                if (!empty($categories)) {
                    echo esc_html($categories[0]->name);
                }
                ?>
            </div>
        </div>
        <div class="post-card-body">
            <time class="post-card-date" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time>
            <h3 class="post-card-title"><?php the_title(); ?></h3>
            <p class="post-card-excerpt"><?php echo esc_html(babarida_truncate(get_the_excerpt(), 120)); ?></p>
            <span class="post-card-read-more">
                <?php esc_html_e('Read More', 'babarida-dive'); ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </span>
        </div>
    </a>
</article>
