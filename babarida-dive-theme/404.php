<?php
/**
 * Babarida Dive Center - 404 Error Page
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

<main id="primary" class="site-main error-404-main" role="main">
    <section class="error-404-section" aria-label="<?php esc_attr_e('Page Not Found', 'babarida-dive'); ?>">
        <div class="container">
            <div class="error-404-inner">
                <div class="error-404-bubbles" aria-hidden="true">
                    <?php for ($i = 1; $i <= 10; $i++) : ?>
                        <div class="bubble bubble-<?php echo esc_attr($i); ?>" style="--bubble-size: <?php echo esc_attr(rand(10, 50)); ?>px; --bubble-left: <?php echo esc_attr(rand(5, 95)); ?>%; --bubble-delay: <?php echo esc_attr(rand(0, 5)); ?>s; --bubble-duration: <?php echo esc_attr(rand(6, 12)); ?>s;"></div>
                    <?php endfor; ?>
                </div>
                <div class="error-404-content" data-animate="fade-up">
                    <span class="error-404-code" aria-hidden="true">404</span>
                    <h1 class="error-404-title"><?php esc_html_e('Lost at Sea?', 'babarida-dive'); ?></h1>
                    <p class="error-404-text">
                        <?php esc_html_e('The page you\'re looking for seems to have drifted away. Let us help you navigate back to crystal-clear waters.', 'babarida-dive'); ?>
                    </p>
                    <div class="error-404-actions">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary btn-magnetic btn-lg">
                            <span class="btn-text"><?php esc_html_e('Back to Home', 'babarida-dive'); ?></span>
                        </a>
                        <a href="<?php echo esc_url(home_url('/destinations/bunaken/')); ?>" class="btn btn-outline-primary btn-magnetic btn-lg">
                            <span class="btn-text"><?php esc_html_e('Explore Bunaken', 'babarida-dive'); ?></span>
                        </a>
                    </div>
                    <div class="error-404-search">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
