<?php
/**
 * Custom Post Types Registration
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

namespace Babarida_Theme;

if (!defined('ABSPATH')) {
    exit;
}

class Babarida_CPT
{

    public function __construct()
    {
        add_action('init', [$this, 'register_destinations']);
        add_action('init', [$this, 'register_liveaboards']);
        add_action('init', [$this, 'register_dive_sites']);
        add_action('init', [$this, 'register_courses']);
        add_action('init', [$this, 'register_water_sports']);

        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes'], 10, 2);

        add_filter('enter_title_here', [$this, 'change_title_placeholder'], 10, 2);
    }

    public function register_destinations(): void
    {
        $labels = array(
            'name'               => _x('Destinations', 'post type general name', 'babarida-dive'),
            'singular_name'      => _x('Destination', 'post type singular name', 'babarida-dive'),
            'menu_name'          => _x('Destinations', 'admin menu', 'babarida-dive'),
            'name_admin_bar'     => _x('Destination', 'add new on admin bar', 'babarida-dive'),
            'add_new'            => _x('Add New', 'destination', 'babarida-dive'),
            'add_new_item'       => __('Add New Destination', 'babarida-dive'),
            'new_item'           => __('New Destination', 'babarida-dive'),
            'edit_item'          => __('Edit Destination', 'babarida-dive'),
            'view_item'          => __('View Destination', 'babarida-dive'),
            'all_items'          => __('All Destinations', 'babarida-dive'),
            'search_items'       => __('Search Destinations', 'babarida-dive'),
            'parent_item_colon'  => __('Parent Destinations:', 'babarida-dive'),
            'not_found'          => __('No destinations found.', 'babarida-dive'),
            'not_found_in_trash' => __('No destinations found in Trash.', 'babarida-dive'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'rest_base'          => 'destinations',
            'has_archive'        => true,
            'rewrite'            => array('slug' => 'destinations', 'with_front' => false),
            'menu_position'      => 5,
            'menu_icon'          => 'dashicons-location-alt',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields'),
            'capability_type'    => 'post',
            'map_meta_cap'       => true,
        );

        register_post_type('destination', $args);
    }

    public function register_liveaboards(): void
    {
        $labels = array(
            'name'               => _x('Liveaboards', 'post type general name', 'babarida-dive'),
            'singular_name'      => _x('Liveaboard', 'post type singular name', 'babarida-dive'),
            'menu_name'          => _x('Liveaboards', 'admin menu', 'babarida-dive'),
            'name_admin_bar'     => _x('Liveaboard', 'add new on admin bar', 'babarida-dive'),
            'add_new'            => _x('Add New', 'liveaboard', 'babarida-dive'),
            'add_new_item'       => __('Add New Liveaboard', 'babarida-dive'),
            'new_item'           => __('New Liveaboard', 'babarida-dive'),
            'edit_item'          => __('Edit Liveaboard', 'babarida-dive'),
            'view_item'          => __('View Liveaboard', 'babarida-dive'),
            'all_items'          => __('All Liveaboards', 'babarida-dive'),
            'search_items'       => __('Search Liveaboards', 'babarida-dive'),
            'not_found'          => __('No liveaboards found.', 'babarida-dive'),
            'not_found_in_trash' => __('No liveaboards found in Trash.', 'babarida-dive'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'rest_base'          => 'liveaboards',
            'has_archive'        => true,
            'rewrite'            => array('slug' => 'liveaboards', 'with_front' => false),
            'menu_position'      => 6,
            'menu_icon'          => 'dashicons-sailboat',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields'),
            'capability_type'    => 'post',
            'map_meta_cap'       => true,
        );

        register_post_type('liveaboard', $args);
    }

    public function register_dive_sites(): void
    {
        $labels = array(
            'name'          => _x('Dive Sites', 'post type general name', 'babarida-dive'),
            'singular_name' => _x('Dive Site', 'post type singular name', 'babarida-dive'),
            'menu_name'     => _x('Dive Sites', 'admin menu', 'babarida-dive'),
            'add_new_item'  => __('Add New Dive Site', 'babarida-dive'),
            'edit_item'     => __('Edit Dive Site', 'babarida-dive'),
            'all_items'     => __('All Dive Sites', 'babarida-dive'),
            'search_items'  => __('Search Dive Sites', 'babarida-dive'),
            'not_found'     => __('No dive sites found.', 'babarida-dive'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'rest_base'          => 'dive-sites',
            'has_archive'        => true,
            'rewrite'            => array('slug' => 'dive-sites', 'with_front' => false),
            'menu_position'      => 7,
            'menu_icon'          => 'dashicons-water',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        );

        register_post_type('dive_site', $args);
    }

    public function register_courses(): void
    {
        $labels = array(
            'name'          => _x('SSI Courses', 'post type general name', 'babarida-dive'),
            'singular_name' => _x('SSI Course', 'post type singular name', 'babarida-dive'),
            'menu_name'     => _x('SSI Courses', 'admin menu', 'babarida-dive'),
            'add_new_item'  => __('Add New Course', 'babarida-dive'),
            'edit_item'     => __('Edit Course', 'babarida-dive'),
            'all_items'     => __('All Courses', 'babarida-dive'),
            'search_items'  => __('Search Courses', 'babarida-dive'),
            'not_found'     => __('No courses found.', 'babarida-dive'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'rest_base'          => 'courses',
            'has_archive'        => true,
            'rewrite'            => array('slug' => 'ssi-courses', 'with_front' => false),
            'menu_position'      => 8,
            'menu_icon'          => 'dashicons-awards',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        );

        register_post_type('course', $args);
    }

    public function register_water_sports(): void
    {
        $labels = array(
            'name'          => _x('Water Sports', 'post type general name', 'babarida-dive'),
            'singular_name' => _x('Water Sport', 'post type singular name', 'babarida-dive'),
            'menu_name'     => _x('Water Sports', 'admin menu', 'babarida-dive'),
            'add_new_item'  => __('Add New Water Sport', 'babarida-dive'),
            'edit_item'     => __('Edit Water Sport', 'babarida-dive'),
            'all_items'     => __('All Water Sports', 'babarida-dive'),
            'search_items'  => __('Search Water Sports', 'babarida-dive'),
            'not_found'     => __('No water sports found.', 'babarida-dive'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'rest_base'          => 'water-sports',
            'has_archive'        => true,
            'rewrite'            => array('slug' => 'water-sports', 'with_front' => false),
            'menu_position'      => 9,
            'menu_icon'          => 'dashicons-smiley',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        );

        register_post_type('water_sport', $args);
    }

    public function add_meta_boxes(): void
    {
        add_meta_box(
            'babarida_destination_details',
            __('Destination Details', 'babarida-dive'),
            [$this, 'render_destination_meta_box'],
            'destination',
            'normal',
            'high'
        );

        add_meta_box(
            'babarida_liveaboard_details',
            __('Liveaboard Details', 'babarida-dive'),
            [$this, 'render_liveaboard_meta_box'],
            'liveaboard',
            'normal',
            'high'
        );

        add_meta_box(
            'babarida_dive_site_details',
            __('Dive Site Details', 'babarida-dive'),
            [$this, 'render_dive_site_meta_box'],
            'dive_site',
            'normal',
            'high'
        );

        add_meta_box(
            'babarida_course_details',
            __('Course Details', 'babarida-dive'),
            [$this, 'render_course_meta_box'],
            'course',
            'normal',
            'high'
        );
    }

    public function render_destination_meta_box(\WP_Post $post): void
    {
        wp_nonce_field('babarida_save_destination_meta', 'babarida_destination_nonce');

        $subtitle   = get_post_meta($post->ID, '_babarida_destination_subtitle', true);
        $dive_sites = get_post_meta($post->ID, '_babarida_destination_dive_sites', true);
        $difficulty = get_post_meta($post->ID, '_babarida_destination_difficulty', true);
        $latitude   = get_post_meta($post->ID, '_babarida_destination_latitude', true);
        $longitude  = get_post_meta($post->ID, '_babarida_destination_longitude', true);
        $best_season = get_post_meta($post->ID, '_babarida_destination_best_season', true);
        $visibility  = get_post_meta($post->ID, '_babarida_destination_visibility', true);
        $temp_range  = get_post_meta($post->ID, '_babarida_destination_temp_range', true);
        ?>
        <div class="babarida-meta-grid">
            <div class="meta-field">
                <label for="dest-subtitle"><?php esc_html_e('Subtitle', 'babarida-dive'); ?></label>
                <input type="text" id="dest-subtitle" name="_babarida_destination_subtitle" value="<?php echo esc_attr($subtitle); ?>" class="widefat">
            </div>
            <div class="meta-field">
                <label for="dest-dive-sites"><?php esc_html_e('Number of Dive Sites', 'babarida-dive'); ?></label>
                <input type="number" id="dest-dive-sites" name="_babarida_destination_dive_sites" value="<?php echo esc_attr($dive_sites); ?>" min="0" class="widefat">
            </div>
            <div class="meta-field">
                <label for="dest-difficulty"><?php esc_html_e('Difficulty Level', 'babarida-dive'); ?></label>
                <select id="dest-difficulty" name="_babarida_destination_difficulty" class="widefat">
                    <option value="" <?php selected($difficulty, ''); ?>><?php esc_html_e('Select...', 'babarida-dive'); ?></option>
                    <option value="Beginner" <?php selected($difficulty, 'Beginner'); ?>><?php esc_html_e('Beginner', 'babarida-dive'); ?></option>
                    <option value="Intermediate" <?php selected($difficulty, 'Intermediate'); ?>><?php esc_html_e('Intermediate', 'babarida-dive'); ?></option>
                    <option value="Advanced" <?php selected($difficulty, 'Advanced'); ?>><?php esc_html_e('Advanced', 'babarida-dive'); ?></option>
                    <option value="All Levels" <?php selected($difficulty, 'All Levels'); ?>><?php esc_html_e('All Levels', 'babarida-dive'); ?></option>
                </select>
            </div>
            <div class="meta-field">
                <label for="dest-latitude"><?php esc_html_e('Latitude', 'babarida-dive'); ?></label>
                <input type="text" id="dest-latitude" name="_babarida_destination_latitude" value="<?php echo esc_attr($latitude); ?>" class="widefat" step="any">
            </div>
            <div class="meta-field">
                <label for="dest-longitude"><?php esc_html_e('Longitude', 'babarida-dive'); ?></label>
                <input type="text" id="dest-longitude" name="_babarida_destination_longitude" value="<?php echo esc_attr($longitude); ?>" class="widefat" step="any">
            </div>
            <div class="meta-field">
                <label for="dest-season"><?php esc_html_e('Best Season', 'babarida-dive'); ?></label>
                <input type="text" id="dest-season" name="_babarida_destination_best_season" value="<?php echo esc_attr($best_season); ?>" class="widefat" placeholder="<?php esc_attr_e('e.g., March - November', 'babarida-dive'); ?>">
            </div>
            <div class="meta-field">
                <label for="dest-visibility"><?php esc_html_e('Visibility Range', 'babarida-dive'); ?></label>
                <input type="text" id="dest-visibility" name="_babarida_destination_visibility" value="<?php echo esc_attr($visibility); ?>" class="widefat" placeholder="<?php esc_attr_e('e.g., 15 - 40m', 'babarida-dive'); ?>">
            </div>
            <div class="meta-field">
                <label for="dest-temp"><?php esc_html_e('Water Temperature Range', 'babarida-dive'); ?></label>
                <input type="text" id="dest-temp" name="_babarida_destination_temp_range" value="<?php echo esc_attr($temp_range); ?>" class="widefat" placeholder="<?php esc_attr_e('e.g., 26 - 30°C', 'babarida-dive'); ?>">
            </div>
        </div>
        <style>.babarida-meta-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:16px}.babarida-meta-grid .meta-field label{display:block;font-weight:600;margin-bottom:4px}.babarida-meta-grid .meta-field input,.babarida-meta-grid .meta-field select{padding:8px 12px;border:1px solid #8c8f94;border-radius:4px;width:100%}</style>
        <?php
    }

    public function render_liveaboard_meta_box(\WP_Post $post): void
    {
        wp_nonce_field('babarida_save_liveaboard_meta', 'babarida_liveaboard_nonce');

        $length     = get_post_meta($post->ID, '_babarida_liveaboard_length', true);
        $guests     = get_post_meta($post->ID, '_babarida_liveaboard_guests', true);
        $cabins     = get_post_meta($post->ID, '_babarida_liveaboard_cabins', true);
        $price_from = get_post_meta($post->ID, '_babarida_liveaboard_price_from', true);
        $built_year = get_post_meta($post->ID, '_babarida_liveaboard_built_year', true);
        $boat_length_meters = get_post_meta($post->ID, '_babarida_liveaboard_boat_length', true);
        $speed      = get_post_meta($post->ID, '_babarida_liveaboard_speed', true);
        ?>
        <div class="babarida-meta-grid">
            <div class="meta-field">
                <label for="lb-length"><?php esc_html_e('Trip Length', 'babarida-dive'); ?></label>
                <input type="text" id="lb-length" name="_babarida_liveaboard_length" value="<?php echo esc_attr($length); ?>" class="widefat" placeholder="<?php esc_attr_e('e.g., 7 Days / 6 Nights', 'babarida-dive'); ?>">
            </div>
            <div class="meta-field">
                <label for="lb-guests"><?php esc_html_e('Max Guests', 'babarida-dive'); ?></label>
                <input type="number" id="lb-guests" name="_babarida_liveaboard_guests" value="<?php echo esc_attr($guests); ?>" min="1" class="widefat">
            </div>
            <div class="meta-field">
                <label for="lb-cabins"><?php esc_html_e('Number of Cabins', 'babarida-dive'); ?></label>
                <input type="number" id="lb-cabins" name="_babarida_liveaboard_cabins" value="<?php echo esc_attr($cabins); ?>" min="1" class="widefat">
            </div>
            <div class="meta-field">
                <label for="lb-price"><?php esc_html_e('Price From (USD)', 'babarida-dive'); ?></label>
                <input type="text" id="lb-price" name="_babarida_liveaboard_price_from" value="<?php echo esc_attr($price_from); ?>" class="widefat" placeholder="<?php esc_attr_e('e.g., $2,500', 'babarida-dive'); ?>">
            </div>
            <div class="meta-field">
                <label for="lb-built"><?php esc_html_e('Year Built', 'babarida-dive'); ?></label>
                <input type="number" id="lb-built" name="_babarida_liveaboard_built_year" value="<?php echo esc_attr($built_year); ?>" min="1900" max="<?php echo esc_attr((int) gmdate('Y') + 1); ?>" class="widefat">
            </div>
            <div class="meta-field">
                <label for="lb-boat-length"><?php esc_html_e('Boat Length (meters)', 'babarida-dive'); ?></label>
                <input type="number" id="lb-boat-length" name="_babarida_liveaboard_boat_length" value="<?php echo esc_attr($boat_length_meters); ?>" min="1" step="0.1" class="widefat">
            </div>
            <div class="meta-field">
                <label for="lb-speed"><?php esc_html_e('Cruising Speed (knots)', 'babarida-dive'); ?></label>
                <input type="number" id="lb-speed" name="_babarida_liveaboard_speed" value="<?php echo esc_attr($speed); ?>" min="1" step="0.1" class="widefat">
            </div>
        </div>
        <?php
    }

    public function render_dive_site_meta_box(\WP_Post $post): void
    {
        wp_nonce_field('babarida_save_dive_site_meta', 'babarida_dive_site_nonce');

        $max_depth     = get_post_meta($post->ID, '_babarida_dive_site_max_depth', true);
        $difficulty    = get_post_meta($post->ID, '_babarida_dive_site_difficulty', true);
        $current       = get_post_meta($post->ID, '_babarida_dive_site_current', true);
        $visibility    = get_post_meta($post->ID, '_babarida_dive_site_visibility', true);
        $marine_life   = get_post_meta($post->ID, '_babarida_dive_site_marine_life', true);
        $destination_id = get_post_meta($post->ID, '_babarida_dive_site_destination', true);
        $latitude      = get_post_meta($post->ID, '_babarida_dive_site_latitude', true);
        $longitude     = get_post_meta($post->ID, '_babarida_dive_site_longitude', true);
        ?>
        <div class="babarida-meta-grid">
            <div class="meta-field">
                <label for="ds-destination"><?php esc_html_e('Destination', 'babarida-dive'); ?></label>
                <select id="ds-destination" name="_babarida_dive_site_destination" class="widefat">
                    <option value=""><?php esc_html_e('Select...', 'babarida-dive'); ?></option>
                    <?php
                    $destinations = get_posts(array('post_type' => 'destination', 'posts_per_page' => -1, 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC'));
                    foreach ($destinations as $dest) :
                        ?>
                        <option value="<?php echo esc_attr($dest->ID); ?>" <?php selected($destination_id, $dest->ID); ?>><?php echo esc_html($dest->post_title); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="meta-field">
                <label for="ds-depth"><?php esc_html_e('Max Depth (m)', 'babarida-dive'); ?></label>
                <input type="number" id="ds-depth" name="_babarida_dive_site_max_depth" value="<?php echo esc_attr($max_depth); ?>" min="0" step="1" class="widefat">
            </div>
            <div class="meta-field">
                <label for="ds-difficulty"><?php esc_html_e('Difficulty', 'babarida-dive'); ?></label>
                <select id="ds-difficulty" name="_babarida_dive_site_difficulty" class="widefat">
                    <option value="" <?php selected($difficulty, ''); ?>><?php esc_html_e('Select...', 'babarida-dive'); ?></option>
                    <option value="Beginner" <?php selected($difficulty, 'Beginner'); ?>>Beginner</option>
                    <option value="Intermediate" <?php selected($difficulty, 'Intermediate'); ?>>Intermediate</option>
                    <option value="Advanced" <?php selected($difficulty, 'Advanced'); ?>>Advanced</option>
                    <option value="Expert" <?php selected($difficulty, 'Expert'); ?>>Expert</option>
                </select>
            </div>
            <div class="meta-field">
                <label for="ds-current"><?php esc_html_e('Current', 'babarida-dive'); ?></label>
                <select id="ds-current" name="_babarida_dive_site_current" class="widefat">
                    <option value="" <?php selected($current, ''); ?>><?php esc_html_e('Select...', 'babarida-dive'); ?></option>
                    <option value="None" <?php selected($current, 'None'); ?>>None</option>
                    <option value="Mild" <?php selected($current, 'Mild'); ?>>Mild</option>
                    <option value="Moderate" <?php selected($current, 'Moderate'); ?>>Moderate</option>
                    <option value="Strong" <?php selected($current, 'Strong'); ?>>Strong</option>
                </select>
            </div>
            <div class="meta-field">
                <label for="ds-visibility"><?php esc_html_e('Visibility (m)', 'babarida-dive'); ?></label>
                <input type="text" id="ds-visibility" name="_babarida_dive_site_visibility" value="<?php echo esc_attr($visibility); ?>" class="widefat" placeholder="<?php esc_attr_e('e.g., 15 - 30', 'babarida-dive'); ?>">
            </div>
            <div class="meta-field">
                <label for="ds-marine-life"><?php esc_html_e('Marine Life', 'babarida-dive'); ?></label>
                <textarea id="ds-marine-life" name="_babarida_dive_site_marine_life" class="widefat" rows="3" placeholder="<?php esc_attr_e('e.g., Reef sharks, turtles, barracuda, nudibranchs', 'babarida-dive'); ?>"><?php echo esc_textarea($marine_life); ?></textarea>
            </div>
            <div class="meta-field">
                <label for="ds-lat"><?php esc_html_e('Latitude', 'babarida-dive'); ?></label>
                <input type="text" id="ds-lat" name="_babarida_dive_site_latitude" value="<?php echo esc_attr($latitude); ?>" class="widefat" step="any">
            </div>
            <div class="meta-field">
                <label for="ds-lng"><?php esc_html_e('Longitude', 'babarida-dive'); ?></label>
                <input type="text" id="ds-lng" name="_babarida_dive_site_longitude" value="<?php echo esc_attr($longitude); ?>" class="widefat" step="any">
            </div>
        </div>
        <?php
    }

    public function render_course_meta_box(\WP_Post $post): void
    {
        wp_nonce_field('babarida_save_course_meta', 'babarida_course_nonce');

        $level       = get_post_meta($post->ID, '_babarida_course_level', true);
        $duration    = get_post_meta($post->ID, '_babarida_course_duration', true);
        $price       = get_post_meta($post->ID, '_babarida_course_price', true);
        $min_age     = get_post_meta($post->ID, '_babarida_course_min_age', true);
        $min_dives   = get_post_meta($post->ID, '_babarida_course_min_dives', true);
        $certification = get_post_meta($post->ID, '_babarida_course_certification', true);
        ?>
        <div class="babarida-meta-grid">
            <div class="meta-field">
                <label for="crs-level"><?php esc_html_e('Level', 'babarida-dive'); ?></label>
                <select id="crs-level" name="_babarida_course_level" class="widefat">
                    <option value="" <?php selected($level, ''); ?>><?php esc_html_e('Select...', 'babarida-dive'); ?></option>
                    <option value="Try Dive" <?php selected($level, 'Try Dive'); ?>>Try Dive</option>
                    <option value="Open Water" <?php selected($level, 'Open Water'); ?>>Open Water</option>
                    <option value="Advanced" <?php selected($level, 'Advanced'); ?>>Advanced</option>
                    <option value="Stress & Rescue" <?php selected($level, 'Stress & Rescue'); ?>>Stress & Rescue</option>
                    <option value="Divemaster" <?php selected($level, 'Divemaster'); ?>>Divemaster</option>
                    <option value="Specialty" <?php selected($level, 'Specialty'); ?>>Specialty</option>
                </select>
            </div>
            <div class="meta-field">
                <label for="crs-duration"><?php esc_html_e('Duration', 'babarida-dive'); ?></label>
                <input type="text" id="crs-duration" name="_babarida_course_duration" value="<?php echo esc_attr($duration); ?>" class="widefat" placeholder="<?php esc_attr_e('e.g., 3-4 Days', 'babarida-dive'); ?>">
            </div>
            <div class="meta-field">
                <label for="crs-price"><?php esc_html_e('Price (USD)', 'babarida-dive'); ?></label>
                <input type="number" id="crs-price" name="_babarida_course_price" value="<?php echo esc_attr($price); ?>" min="0" step="0.01" class="widefat">
            </div>
            <div class="meta-field">
                <label for="crs-min-age"><?php esc_html_e('Minimum Age', 'babarida-dive'); ?></label>
                <input type="number" id="crs-min-age" name="_babarida_course_min_age" value="<?php echo esc_attr($min_age); ?>" min="0" class="widefat">
            </div>
            <div class="meta-field">
                <label for="crs-min-dives"><?php esc_html_e('Minimum Logged Dives', 'babarida-dive'); ?></label>
                <input type="number" id="crs-min-dives" name="_babarida_course_min_dives" value="<?php echo esc_attr($min_dives); ?>" min="0" class="widefat">
            </div>
            <div class="meta-field">
                <label for="crs-cert"><?php esc_html_e('Certification', 'babarida-dive'); ?></label>
                <input type="text" id="crs-cert" name="_babarida_course_certification" value="<?php echo esc_attr($certification); ?>" class="widefat" placeholder="<?php esc_attr_e('e.g., SSI Open Water Diver', 'babarida-dive'); ?>">
            </div>
        </div>
        <?php
    }

    public function save_meta_boxes(int $post_id, \WP_Post $post): void
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        $nonce_fields = array(
            'destination' => array('nonce' => 'babarida_destination_nonce', 'action' => 'babarida_save_destination_meta'),
            'liveaboard'  => array('nonce' => 'babarida_liveaboard_nonce', 'action' => 'babarida_save_liveaboard_meta'),
            'dive_site'   => array('nonce' => 'babarida_dive_site_nonce', 'action' => 'babarida_save_dive_site_meta'),
            'course'      => array('nonce' => 'babarida_course_nonce', 'action' => 'babarida_save_course_meta'),
        );

        foreach ($nonce_fields as $type => $config) {
            if ($post->post_type !== $type) {
                continue;
            }
            if (!isset($_POST[$config['nonce']]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$config['nonce']])), $config['action'])) {
                return;
            }
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }

            $meta_keys = array(
                'destination' => array(
                    '_babarida_destination_subtitle',
                    '_babarida_destination_dive_sites',
                    '_babarida_destination_difficulty',
                    '_babarida_destination_latitude',
                    '_babarida_destination_longitude',
                    '_babarida_destination_best_season',
                    '_babarida_destination_visibility',
                    '_babarida_destination_temp_range',
                ),
                'liveaboard' => array(
                    '_babarida_liveaboard_length',
                    '_babarida_liveaboard_guests',
                    '_babarida_liveaboard_cabins',
                    '_babarida_liveaboard_price_from',
                    '_babarida_liveaboard_built_year',
                    '_babarida_liveaboard_boat_length',
                    '_babarida_liveaboard_speed',
                ),
                'dive_site' => array(
                    '_babarida_dive_site_destination',
                    '_babarida_dive_site_max_depth',
                    '_babarida_dive_site_difficulty',
                    '_babarida_dive_site_current',
                    '_babarida_dive_site_visibility',
                    '_babarida_dive_site_marine_life',
                    '_babarida_dive_site_latitude',
                    '_babarida_dive_site_longitude',
                ),
                'course' => array(
                    '_babarida_course_level',
                    '_babarida_course_duration',
                    '_babarida_course_price',
                    '_babarida_course_min_age',
                    '_babarida_course_min_dives',
                    '_babarida_course_certification',
                ),
            );

            if (isset($meta_keys[$type])) {
                foreach ($meta_keys[$type] as $key) {
                    if (isset($_POST[$key])) {
                        $value = wp_unslash($_POST[$key]);
                        if (is_numeric($value)) {
                            update_post_meta($post_id, $key, absint($value) == $value ? (int) $value : (float) $value);
                        } else {
                            update_post_meta($post_id, $key, sanitize_text_field($value));
                        }
                    }
                }
            }
        }
    }

    public function change_title_placeholder(string $title, \WP_Post $post): string
    {
        switch ($post->post_type) {
            case 'destination':
                return __('Enter destination name (e.g., Bunaken)', 'babarida-dive');
            case 'liveaboard':
                return __('Enter liveaboard name (e.g., MV Babarida Explorer)', 'babarida-dive');
            case 'dive_site':
                return __('Enter dive site name (e.g., Lekuan Walls)', 'babarida-dive');
            case 'course':
                return __('Enter course name (e.g., SSI Open Water Diver)', 'babarida-dive');
            case 'water_sport':
                return __('Enter water sport name (e.g., Jet Ski Safari)', 'babarida-dive');
            default:
                return $title;
        }
    }
}
