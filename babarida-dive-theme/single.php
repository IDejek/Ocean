<?php
/**
 * Babarida Dive Center - Single Post Template
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

<main id="primary" class="site-main single-main" role="main">
    <?php if ($show_breadcrumb) : ?>
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
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-article'); ?>>
            <div class="container">
                <div class="single-layout">
                    <div class="single-content-col">
                        <header class="single-header">
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) :
                                ?>
                                <div class="single-categories">
                                    <?php
                                    foreach (array_slice($categories, 0, 3) as $cat) :
                                        ?>
                                        <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="single-category-tag"><?php echo esc_html($cat->name); ?></a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <h1 class="single-title"><?php the_title(); ?></h1>

                            <div class="single-meta">
                                <div class="single-meta-left">
                                    <span class="single-author">
                                        <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', array('class' => 'author-avatar')); ?>
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html(get_the_author()); ?></a>
                                    </span>
                                    <time class="single-date" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date()); ?></time>
                                </div>
                                <div class="single-meta-right">
                                    <span class="single-read-time"><?php echo esc_html(babarida_reading_time()); ?></span>
                                </div>
                            </div>
                        </header>

                        <?php if (has_post_thumbnail()) : ?>
                            <figure class="single-featured-image">
                                <?php
                                the_post_thumbnail(
                                    'blog-card',
                                    array(
                                        'class'   => 'single-featured-img',
                                        'loading' => 'eager',
                                        'width'   => 800,
                                        'height'  => 500,
                                    )
                                );
                                ?>
                                <?php
                                $caption = get_the_post_thumbnail_caption();
                                if ($caption) :
                                    ?>
                                    <figcaption class="wp-caption-text"><?php echo esc_html($caption); ?></figcaption>
                                <?php endif; ?>
                            </figure>
                        <?php endif; ?>

                        <div class="single-entry-content">
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

                        <?php if (has_tag()) : ?>
                            <div class="single-tags">
                                <span class="tags-label"><?php esc_html_e('Tags:', 'babarida-dive'); ?></span>
                                <div class="tags-list">
                                    <?php the_tags('', ''); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="single-share">
                            <span class="share-label"><?php esc_html_e('Share:', 'babarida-dive'); ?></span>
                            <div class="share-buttons">
                                <a href="<?php echo esc_url(babarida_share_url('facebook')); ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-facebook" aria-label="<?php esc_attr_e('Share on Facebook', 'babarida-dive'); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                                </a>
                                <a href="<?php echo esc_url(babarida_share_url('twitter')); ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-twitter" aria-label="<?php esc_attr_e('Share on X', 'babarida-dive'); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                                <a href="<?php echo esc_url(babarida_share_url('whatsapp')); ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-whatsapp" aria-label="<?php esc_attr_e('Share on WhatsApp', 'babarida-dive'); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Author Box -->
                        <div class="author-box">
                            <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', array('class' => 'author-box-avatar')); ?>
                            <div class="author-box-info">
                                <h4 class="author-box-name">
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo esc_html(get_the_author()); ?></a>
                                </h4>
                                <p class="author-box-bio"><?php echo esc_html(get_the_author_meta('description') ?: __('Dive enthusiast and contributor at Babarida Dive Center.', 'babarida-dive')); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php get_sidebar(); ?>
                </div>
            </div>
        </article>

        <!-- Post Navigation -->
        <div class="container">
            <nav class="post-navigation" aria-label="<?php esc_attr_e('Post navigation', 'babarida-dive'); ?>">
                <div class="nav-links">
                    <?php
                    $prev = get_previous_post();
                    $next = get_next_post();
                    if (!empty($prev)) :
                        ?>
                        <a href="<?php echo esc_url(get_permalink($prev)); ?>" class="nav-prev">
                            <span class="nav-label"><?php esc_html_e('Previous', 'babarida-dive'); ?></span>
                            <span class="nav-title"><?php echo esc_html($prev->post_title); ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($next)) : ?>
                        <a href="<?php echo esc_url(get_permalink($next)); ?>" class="nav-next">
                            <span class="nav-label"><?php esc_html_e('Next', 'babarida-dive'); ?></span>
                            <span class="nav-title"><?php echo esc_html($next->post_title); ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>

        <!-- Comments -->
        <?php
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
