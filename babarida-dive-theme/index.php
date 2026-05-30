<?php
/**
 * Babarida Dive Center - Main Index Template
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 * @since 1.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main" role="main">
    <?php
    if (have_posts()) :

        if (is_home() && !is_front_page()) :
            ?>
            <header class="page-header archive-header">
                <div class="container">
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                </div>
            </header>
            <?php
        endif;

        while (have_posts()) :
            the_post();
            get_template_part('template-parts/content', get_post_type());
        endwhile;

        the_posts_pagination(
            array(
                'mid_size'  => 2,
                'prev_text' => sprintf(
                    '<span class="nav-prev-text">%s</span>',
                    esc_html__('&larr; Previous', 'babarida-dive')
                ),
                'next_text' => sprintf(
                    '<span class="nav-next-text">%s</span>',
                    esc_html__('Next &rarr;', 'babarida-dive')
                ),
                'class'     => 'babarida-pagination',
            )
        );

    else :
        get_template_part('template-parts/content', 'none');
    endif;
    ?>
</main>

<?php
get_sidebar();
get_footer();
