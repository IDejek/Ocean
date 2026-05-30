<?php
/**
 * Babarida Dive Center - Page Template
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();

 $show_breadcrumb = apply_filters('babarida_show_breadcrumb', true);
?>

<main id="primary" class="site-main page-main" role="main">
    <?php if ($show_breadcrumb && !is_front_page()) : ?>
        <nav class="breadcrumb-nav" aria-label="<?php esc_attr_e('Breadcrumb', 'babarida-dive'); ?>">
            <div class="container">
                <?php babarida_breadcrumb(); ?>
            </div>
        </nav>
    <?php endif; ?>

    <?php
    while (have_posts()) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('page-article'); ?>>
            <?php if (has_post_thumbnail() && !is_front_page()) : ?>
                <div class="page-hero">
                    <div class="page-hero-bg">
                        <?php
                        the_post_thumbnail(
                            'hero-full',
                            array(
                                'class'   => 'page-hero-img',
                                'loading' => 'eager',
                            )
                        );
                        ?>
                    </div>
                    <div class="page-hero-overlay"></div>
                    <div class="container">
                        <div class="page-hero-content">
                            <h1 class="page-hero-title"><?php the_title(); ?></h1>
                        </div>
                    </div>
                </div>
            <?php elseif (!is_front_page()) : ?>
                <div class="page-title-bar">
                    <div class="container">
                        <h1 class="page-title"><?php the_title(); ?></h1>
                    </div>
                </div>
            <?php endif; ?>

            <div class="container">
                <div class="page-content-wrapper">
                    <div class="page-entry-content">
                        <?php
                        the_content();

                        wp_link_pages(
                            array(
                                'before'      => '<div class="page-links">',
                                'after'       => '</div>',
                                'link_before' => '<span>',
                                'link_after'  => '</span>',
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
