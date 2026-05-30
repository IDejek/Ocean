<?php
/**
 * Dynamic Sitemap & Robots.txt Generator
 * 
 * @package Babarida_Core
 * @version 7.0.0
 */

declare(strict_types=1);

namespace BabaridaCore;

if (!defined('ABSPATH')) {
    exit;
}

class Babarida_Seo_Sitemaps
{

    public function __construct()
    {
        add_action('init', [$this, 'add_rewrite_rules']);
        add_action('template_redirect', [$this, 'handle_template_redirect']);
        add_action('publish_post', [$this, 'ping_search_engines']);
        add_action('publish_destination', [$this, 'ping_search_engines']);
        add_action('publish_liveaboard', [$this, 'ping_search_engines']);
    }

    /**
     * Add rewrite rules for sitemap.xml and robots.txt
     */
    public function add_rewrite_rules(): void
    {
        add_rewrite_rule(
            '^sitemap\.xml$',
            'index.php?babarida_sitemap=1',
            'top'
        );
        add_rewrite_rule(
            '^sitemap_index\.xml$',
            'index.php?babarida_sitemap_index=1',
            'top'
        );
        add_rewrite_rule(
            '^robots\.txt$',
            'index.php?babarida_robots=1',
            'top'
        );

        add_filter('query_vars', function (array $vars): array {
            $vars[] = 'babarida_sitemap';
            $vars[] = 'babarida_sitemap_index';
            $vars[] = 'babarida_robots';
            return $vars;
        });
    }

    /**
     * Handle virtual sitemap and robots requests
     */
    public function handle_template_redirect(): void
    {
        if (get_query_var('babarida_sitemap')) {
            $this->output_sitemap();
        } elseif (get_query_var('babarida_sitemap_index')) {
            $this->output_sitemap_index();
        } elseif (get_query_var('babarida_robots')) {
            $this->output_robots();
        }
    }

    /**
     * Output Sitemap Index
     */
    private function output_sitemap_index(): void
    {
        header('Content-Type: application/xml; charset=' . get_option('blog_charset'), true);
        header('X-Robots-Tag: noindex', true);
        echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>';
        ?>
        <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            <sitemap>
                <loc><?php echo esc_url(home_url('/sitemap.xml')); ?></loc>
                <lastmod><?php echo esc_html($this->get_latest_post_date()); ?></lastmod>
            </sitemap>
        </sitemapindex>
        <?php
        exit;
    }

    /**
     * Output Main XML Sitemap
     */
    private function output_sitemap(): void
    {
        header('Content-Type: application/xml; charset=' . get_option('blog_charset'), true);
        echo '<?xml version="1.0" encoding="' . get_option('blog_charset') . '"?' . '>';
        ?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
            
            <!-- Homepage -->
            <url>
                <loc><?php echo esc_url(home_url('/')); ?></loc>
                <lastmod><?php echo esc_html($this->get_latest_post_date()); ?></lastmod>
                <changefreq>daily</changefreq>
                <priority>1.0</priority>
            </url>

            <!-- Destinations -->
            <?php 
            $destinations = get_posts(array(
                'post_type'   => 'destination',
                'numberposts' => 50,
                'post_status' => 'publish',
            ));
            foreach ($destinations as $post) : ?>
            <url>
                <loc><?php echo esc_url(get_permalink($post->ID)); ?></loc>
                <lastmod><?php echo esc_html(get_the_modified_date('c', $post->ID)); ?></lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.9</priority>
            </url>
            <?php endforeach; ?>

            <!-- Liveaboards -->
            <?php 
            $liveaboards = get_posts(array(
                'post_type'   => 'liveaboard',
                'numberposts' => 50,
                'post_status' => 'publish',
            ));
            foreach ($liveaboards as $post) : ?>
            <url>
                <loc><?php echo esc_url(get_permalink($post->ID)); ?></loc>
                <lastmod><?php echo esc_html(get_the_modified_date('c', $post->ID)); ?></lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.9</priority>
            </url>
            <?php endforeach; ?>

            <!-- Dive Sites -->
            <?php 
            $dive_sites = get_posts(array(
                'post_type'   => 'dive_site',
                'numberposts' => 100,
                'post_status' => 'publish',
            ));
            foreach ($dive_sites as $post) : ?>
            <url>
                <loc><?php echo esc_url(get_permalink($post->ID)); ?></loc>
                <lastmod><?php echo esc_html(get_the_modified_date('c', $post->ID)); ?></lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.8</priority>
            </url>
            <?php endforeach; ?>

            <!-- SSI Courses -->
            <?php 
            $courses = get_posts(array(
                'post_type'   => 'course',
                'numberposts' => 50,
                'post_status' => 'publish',
            ));
            foreach ($courses as $post) : ?>
            <url>
                <loc><?php echo esc_url(get_permalink($post->ID)); ?></loc>
                <lastmod><?php echo esc_html(get_the_modified_date('c', $post->ID)); ?></lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.8</priority>
            </url>
            <?php endforeach; ?>

            <!-- Blog Posts -->
            <?php 
            $posts = get_posts(array(
                'post_type'   => 'post',
                'numberposts' => 100,
                'post_status' => 'publish',
            ));
            foreach ($posts as $post) : ?>
            <url>
                <loc><?php echo esc_url(get_permalink($post->ID)); ?></loc>
                <lastmod><?php echo esc_html(get_the_modified_date('c', $post->ID)); ?></lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.7</priority>
            </url>
            <?php endforeach; ?>

            <!-- Pages -->
            <?php 
            $pages = get_posts(array(
                'post_type'   => 'page',
                'numberposts' => 50,
                'post_status' => 'publish',
            ));
            foreach ($pages as $post) : ?>
            <url>
                <loc><?php echo esc_url(get_permalink($post->ID)); ?></loc>
                <lastmod><?php echo esc_html(get_the_modified_date('c', $post->ID)); ?></lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.6</priority>
            </url>
            <?php endforeach; ?>

        </urlset>
        <?php
        exit;
    }

    /**
     * Output Virtual robots.txt
     */
    private function output_robots(): void
    {
        header('Content-Type: text/plain; charset=utf-8', true);
        
        $site_url = home_url('/');
        $sitemap_url = home_url('/sitemap.xml');

        echo "User-agent: *\n";
        echo "Allow: /\n";
        echo "Disallow: /wp-admin/\n";
        echo "Disallow: /wp-includes/\n";
        echo "Disallow: /wp-content/plugins/\n";
        echo "Disallow: /trackback/\n";
        echo "Disallow: /?s=\n";
        echo "Disallow: /search/\n";
        echo "Disallow: */page/\n"; // Prevent paginated archives from diluting crawl budget
        
        // Disallow internal plugin pages
        echo "Disallow: /?babarida_\n";
        
        echo "\nSitemap: " . esc_url($sitemap_url) . "\n";
        
        // Add Google Search Console verification if set in customizer
        $google_verify = get_theme_mod('babarida_google_site_verification', '');
        if (!empty($google_verify)) {
            // Note: Verification usually goes in <head>, but some bots support it here
        }
        
        exit;
    }

    /**
     * Get the latest post modified date across all public post types
     */
    private function get_latest_post_date(): string
    {
        global $wpdb;
        $date = $wpdb->get_var(
            "SELECT MAX(post_modified_gmt) FROM {$wpdb->posts} 
             WHERE post_status = 'publish' AND post_type IN ('post', 'page', 'destination', 'liveaboard', 'dive_site', 'course', 'water_sport')"
        );
        return $date ? gmdate('c', strtotime($date)) : gmdate('c');
    }

    /**
     * Ping search engines on new content publication
     */
    public function ping_search_engines(): void
    {
        $sitemap_url = urlencode(home_url('/sitemap.xml'));
        
        $engines = array(
            'google' => 'https://www.google.com/ping?sitemap=' . $sitemap_url,
            'bing'   => 'https://www.bing.com/ping?sitemap=' . $sitemap_url,
        );

        foreach ($engines as $engine => $url) {
            wp_remote_get($url, array('timeout' => 3, 'blocking' => false));
        }
    }
}
