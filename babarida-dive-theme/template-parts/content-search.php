<?php
/**
 * Template Part: Search Result Content
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('search-card'); ?> data-animate="fade-up">
    <a href="<?php the_permalink(); ?>" class="search-card-link">
        <?php if (has_post_thumbnail()) : ?>
            <div class="search-card-thumb">
                <?php the_post_thumbnail('thumbnail', array('loading' => 'lazy')); ?>
            </div>
        <?php endif; ?>
        <div class="search-card-body">
            <span class="search-card-type"><?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name ?? get_post_type()); ?></span>
            <h3 class="search-card-title"><?php the_title(); ?></h3>
            <p class="search-card-excerpt"><?php echo esc_html(babarida_truncate(get_the_excerpt(), 150)); ?></p>
        </div>
    </a>
</article>
