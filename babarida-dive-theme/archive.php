<?php
/**
 * Babarida Dive Center - Archive Template
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();

 $queried_object = get_queried_object();
 $archive_title  = '';
 $archive_desc   = '';

if (is_category()) {
    $archive_title = single_cat_title('', false);
    $archive_desc  = category_description();
} elseif (is_tag()) {
    $archive_title = single_tag_title('', false);
    $archive_desc  = tag_description();
} elseif (is_author()) {
    $archive_title = get_the_author();
    $archive_desc  = get_the_author_meta('description');
} elseif (is_year()) {
    $archive_title = get_the_date('Y');
} elseif (is_month()) {
    $archive_title = get_the_date('F Y');
} elseif (is_day()) {
    $archive_title = get_the_date('F j, Y');
} elseif (is_post_type_archive()) {
    $archive_title = post_type_archive_title('', false);
}
?>

<main id="primary" class="site-main archive-main" role="main">
    <nav class="breadcrumb-nav" aria-label="<?php esc_attr_e('Breadcrumb', 'babarida-dive'); ?>">
        <div class="container">
            <?php babarida_breadcrumb(); ?>
        </div>
    </nav>

    <header class="archive-header section-padding-sm">
        <div class="container">
            <?php if ($archive_title) : ?>
                <h1 class="archive-title" data-animate="fade-up"><?php echo esc_html($archive_title); ?></h1>
            <?php endif; ?>
            <?php if ($archive_desc) : ?>
                <div class="archive-description" data-animate="fade-up" data-delay="100"><?php echo wp_kses_post($archive_desc); ?></div>
            <?php endif; ?>
        </div>
    </header>

    <div class="container">
        <div class="archive-layout">
            <div class="archive-content">
                <?php if (have_posts()) : ?>
                    <div class="archive-grid">
                        <?php
                        while (have_posts()) :
                            the_post();
                            get_template_part('template-parts/content', get_post_type());
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
                    get_template_part('template-parts/content', 'none');
                endif;
                ?>
            </div>
            <?php get_sidebar(); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
