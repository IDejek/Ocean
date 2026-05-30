<?php
/**
 * Babarida Dive Center - Search Results Template
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main search-main" role="main">
    <div class="container">
        <header class="search-header" data-animate="fade-up">
            <h1 class="search-title">
                <?php
                printf(
                    esc_html__('Search Results for: %s', 'babarida-dive'),
                    '<span>' . esc_html(get_search_query()) . '</span>'
                );
                ?>
            </h1>
            <div class="search-header-form">
                <?php get_search_form(); ?>
            </div>
        </header>

        <?php if (have_posts()) : ?>
            <div class="search-results-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', 'search');
                endwhile;
                ?>
            </div>
            <?php
            the_posts_pagination(
                array(
                    'mid_size'  => 2,
                    'prev_text' => '&larr; ' . esc_html__('Previous', 'babarida-dive'),
                    'next_text' => esc_html__('Next', 'babarida-dive') . ' &rarr;',
                )
            );
        else :
            ?>
            <div class="search-no-results" data-animate="fade-up">
                <p><?php esc_html_e('No results found. Try a different search term.', 'babarida-dive'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
