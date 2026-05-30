<?php
/**
 * Plugin Name: Babarida Core
 * Plugin URI: https://babaridadive.com
 * Description: Enterprise backend engine for Babarida Dive Center. Handles Booking Engine, CRM, Availability, Dynamic Pricing, Payments, Memberships, Partners, and Admin Dashboard.
 * Version: 7.0.0
 * Author: Iqbal Tombinawa
 * Author URI: https://babaridadive.com
 * License: Proprietary
 * Text Domain: babarida-core
 * Domain Path: /languages
 * Requires at least: 6.5
 * Requires PHP: 8.4
 */

declare(strict_types=1);

namespace BabaridaCore;

if (!defined('ABSPATH')) {
    exit;
}

define('BABARIDA_CORE_VERSION', '7.0.0');
define('BABARIDA_CORE_DIR', plugin_dir_path(__FILE__));
define('BABARIDA_CORE_URI', plugin_dir_url(__FILE__));
define('BABARIDA_CORE_FILE', __FILE__);

/* ─────────────────────────────────────────────
   PLUGIN ACTIVATION & DATABASE SCHEMA
   ───────────────────────────────────────────── */
register_activation_hook(BABARIDA_CORE_FILE, __NAMESPACE__ . '\\activate_babarida_core');

function activate_babarida_core(): void
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // Using direct queries instead of dbDelta to preserve GENERATED columns and strict ERD compliance
    $sql = "
    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_customers` (
        `customer_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` BIGINT UNSIGNED NOT NULL,
        `first_name` VARCHAR(100) NOT NULL,
        `last_name` VARCHAR(100) NOT NULL,
        `nationality` VARCHAR(3) DEFAULT NULL,
        `cert_level` VARCHAR(50) DEFAULT NULL,
        `cert_agency` VARCHAR(50) DEFAULT NULL,
        `cert_number` VARCHAR(100) DEFAULT NULL,
        `phone` VARCHAR(30) DEFAULT NULL,
        `whatsapp` VARCHAR(30) DEFAULT NULL,
        `passport_number` VARCHAR(50) DEFAULT NULL,
        `passport_expiry` DATE DEFAULT NULL,
        `passport_url` VARCHAR(500) DEFAULT NULL,
        `date_of_birth` DATE DEFAULT NULL,
        `gender` ENUM('male','female','other','prefer_not_to_say') DEFAULT 'prefer_not_to_say',
        `emergency_name` VARCHAR(200) DEFAULT NULL,
        `emergency_phone` VARCHAR(30) DEFAULT NULL,
        `emergency_relation` VARCHAR(100) DEFAULT NULL,
        `avatar_url` VARCHAR(500) DEFAULT NULL,
        `vip_level` ENUM('bronze','silver','gold','platinum','diamond') DEFAULT 'bronze',
        `total_points` INT UNSIGNED DEFAULT 0,
        `total_dives` INT UNSIGNED DEFAULT 0,
        `total_bookings` INT UNSIGNED DEFAULT 0,
        `total_spent` DECIMAL(15,2) DEFAULT 0.00,
        `favorite_destination` BIGINT UNSIGNED DEFAULT NULL,
        `notes` TEXT DEFAULT NULL,
        `status` ENUM('active','inactive','banned') DEFAULT 'active',
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`customer_id`),
        UNIQUE KEY `idx_user_id` (`user_id`),
        KEY `idx_nationality` (`nationality`),
        KEY `idx_vip_level` (`vip_level`),
        KEY `idx_status` (`status`),
        CONSTRAINT `fk_customer_user` FOREIGN KEY (`user_id`) REFERENCES `{$wpdb->users}` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_customer_meta` (
        `meta_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `customer_id` BIGINT UNSIGNED NOT NULL,
        `meta_key` VARCHAR(255) NOT NULL,
        `meta_value` LONGTEXT DEFAULT NULL,
        PRIMARY KEY (`meta_id`),
        KEY `idx_customer_key` (`customer_id`, `meta_key`(191)),
        CONSTRAINT `fk_cmeta_customer` FOREIGN KEY (`customer_id`) REFERENCES `{$wpdb->prefix}babarida_customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_bookings` (
        `booking_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `booking_code` VARCHAR(20) NOT NULL,
        `customer_id` BIGINT UNSIGNED NOT NULL,
        `trip_type` ENUM('liveaboard','day_trip','dive_stay','snorkeling','dive_safari','water_sports','course','fam_island') NOT NULL,
        `destination_id` BIGINT UNSIGNED DEFAULT NULL,
        `liveaboard_id` BIGINT UNSIGNED DEFAULT NULL,
        `cabin_id` BIGINT UNSIGNED DEFAULT NULL,
        `product_id` BIGINT UNSIGNED DEFAULT NULL,
        `check_in_date` DATE NOT NULL,
        `check_out_date` DATE DEFAULT NULL,
        `guests_count` TINYINT UNSIGNED NOT NULL DEFAULT 1,
        `adults` TINYINT UNSIGNED DEFAULT 1,
        `children` TINYINT UNSIGNED DEFAULT 0,
        `diver_count` TINYINT UNSIGNED DEFAULT 1,
        `non_diver_count` TINYINT UNSIGNED DEFAULT 0,
        `subtotal` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `discount_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `tax_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `total_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `currency` VARCHAR(3) NOT NULL DEFAULT 'USD',
        `deposit_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `deposit_paid` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `balance_due` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `status` ENUM('pending','confirmed','paid','checked_in','completed','cancelled','refunded') NOT NULL DEFAULT 'pending',
        `qr_code` VARCHAR(500) DEFAULT NULL,
        `pickup_hotel` VARCHAR(200) DEFAULT NULL,
        `pickup_address` TEXT DEFAULT NULL,
        `pickup_time` TIME DEFAULT NULL,
        `special_requests` TEXT DEFAULT NULL,
        `dietary_requirements` TEXT DEFAULT NULL,
        `medical_conditions` TEXT DEFAULT NULL,
        `coupon_code` VARCHAR(50) DEFAULT NULL,
        `coupon_discount` DECIMAL(15,2) DEFAULT 0.00,
        `assigned_guide_id` BIGINT UNSIGNED DEFAULT NULL,
        `admin_notes` TEXT DEFAULT NULL,
        `cancelled_at` DATETIME DEFAULT NULL,
        `cancellation_reason` TEXT DEFAULT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`booking_id`),
        UNIQUE KEY `idx_booking_code` (`booking_code`),
        KEY `idx_customer_id` (`customer_id`),
        KEY `idx_trip_type` (`trip_type`),
        KEY `idx_status` (`status`),
        KEY `idx_check_in` (`check_in_date`),
        CONSTRAINT `fk_booking_customer` FOREIGN KEY (`customer_id`) REFERENCES `{$wpdb->prefix}babarida_customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_booking_meta` (
        `meta_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `booking_id` BIGINT UNSIGNED NOT NULL,
        `meta_key` VARCHAR(255) NOT NULL,
        `meta_value` LONGTEXT DEFAULT NULL,
        PRIMARY KEY (`meta_id`),
        KEY `idx_booking_key` (`booking_id`, `meta_key`(191)),
        CONSTRAINT `fk_bmeta_booking` FOREIGN KEY (`booking_id`) REFERENCES `{$wpdb->prefix}babarida_bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_cabins` (
        `cabin_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `liveaboard_id` BIGINT UNSIGNED NOT NULL,
        `cabin_name` VARCHAR(200) NOT NULL,
        `cabin_type` ENUM('single','double','twin','triple','quad','master','suite','deluxe') NOT NULL,
        `deck_level` VARCHAR(50) DEFAULT NULL,
        `capacity` TINYINT UNSIGNED NOT NULL DEFAULT 2,
        `bed_config` VARCHAR(100) DEFAULT NULL,
        `bathroom_type` ENUM('shared','ensuite','private') DEFAULT 'ensuite',
        `ac_type` VARCHAR(50) DEFAULT 'individual',
        `amenities` JSON DEFAULT NULL,
        `gallery` JSON DEFAULT NULL,
        `base_price_per_night` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `currency` VARCHAR(3) DEFAULT 'USD',
        `min_nights` TINYINT UNSIGNED DEFAULT 1,
        `sort_order` INT DEFAULT 0,
        `status` ENUM('active','inactive','maintenance') DEFAULT 'active',
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`cabin_id`),
        KEY `idx_liveaboard` (`liveaboard_id`),
        KEY `idx_status` (`status`),
        CONSTRAINT `fk_cabin_liveaboard` FOREIGN KEY (`liveaboard_id`) REFERENCES `{$wpdb->posts}` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_availability` (
        `avail_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `resource_type` ENUM('liveaboard','cabin','day_trip','course','water_sport','dive_site') NOT NULL,
        `resource_id` BIGINT UNSIGNED NOT NULL,
        `date` DATE NOT NULL,
        `total_slots` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
        `booked_slots` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
        `blocked_slots` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
        `available_slots` SMALLINT UNSIGNED GENERATED ALWAYS AS (`total_slots` - `booked_slots` - `blocked_slots`) STORED,
        `status` ENUM('available','limited','sold_out','blocked','closed') NOT NULL DEFAULT 'available',
        `notes` TEXT DEFAULT NULL,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`avail_id`),
        UNIQUE KEY `idx_resource_date` (`resource_type`, `resource_id`, `date`),
        KEY `idx_date` (`date`),
        KEY `idx_available` (`available_slots`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_pricing` (
        `pricing_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `resource_type` ENUM('liveaboard','cabin','day_trip','course','water_sport','package') NOT NULL,
        `resource_id` BIGINT UNSIGNED NOT NULL,
        `season_name` VARCHAR(100) DEFAULT NULL,
        `season_type` ENUM('low','shoulder','high','peak','special') NOT NULL,
        `start_date` DATE NOT NULL,
        `end_date` DATE NOT NULL,
        `base_price` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `currency` VARCHAR(3) NOT NULL DEFAULT 'USD',
        `weekend_markup_pct` DECIMAL(5,2) DEFAULT 0.00,
        `holiday_markup_pct` DECIMAL(5,2) DEFAULT 0.00,
        `min_guests` TINYINT UNSIGNED DEFAULT 1,
        `max_guests` TINYINT UNSIGNED DEFAULT 20,
        `early_bird_days` INT UNSIGNED DEFAULT 0,
        `early_bird_discount_pct` DECIMAL(5,2) DEFAULT 0.00,
        `last_minute_days` INT UNSIGNED DEFAULT 0,
        `last_minute_discount_pct` DECIMAL(5,2) DEFAULT 0.00,
        `group_min_guests` TINYINT UNSIGNED DEFAULT 0,
        `group_discount_pct` DECIMAL(5,2) DEFAULT 0.00,
        `single_supplement_pct` DECIMAL(5,2) DEFAULT 0.00,
        `non_diver_discount_pct` DECIMAL(5,2) DEFAULT 0.00,
        `extra_night_price` DECIMAL(15,2) DEFAULT 0.00,
        `status` ENUM('active','inactive','expired') DEFAULT 'active',
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`pricing_id`),
        KEY `idx_resource` (`resource_type`, `resource_id`),
        KEY `idx_season` (`season_type`),
        KEY `idx_dates` (`start_date`, `end_date`),
        KEY `idx_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_payments` (
        `payment_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `booking_id` BIGINT UNSIGNED NOT NULL,
        `payment_type` ENUM('deposit','full','balance','refund','partial_refund') NOT NULL,
        `method` ENUM('midtrans','xendit','stripe','paypal','bank_transfer','cash','other') NOT NULL,
        `gateway` VARCHAR(50) DEFAULT NULL,
        `amount` DECIMAL(15,2) NOT NULL,
        `currency` VARCHAR(3) NOT NULL DEFAULT 'USD',
        `exchange_rate` DECIMAL(15,6) DEFAULT NULL,
        `original_amount` DECIMAL(15,2) DEFAULT NULL,
        `original_currency` VARCHAR(3) DEFAULT NULL,
        `transaction_id` VARCHAR(255) DEFAULT NULL,
        `external_ref` VARCHAR(255) DEFAULT NULL,
        `va_number` VARCHAR(50) DEFAULT NULL,
        `payment_url` VARCHAR(500) DEFAULT NULL,
        `expiry_time` DATETIME DEFAULT NULL,
        `status` ENUM('pending','processing','completed','failed','expired','refunded','cancelled') NOT NULL DEFAULT 'pending',
        `payload_request` JSON DEFAULT NULL,
        `payload_response` JSON DEFAULT NULL,
        `paid_at` DATETIME DEFAULT NULL,
        `refund_amount` DECIMAL(15,2) DEFAULT NULL,
        `refund_reason` TEXT DEFAULT NULL,
        `refunded_at` DATETIME DEFAULT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`payment_id`),
        KEY `idx_booking` (`booking_id`),
        KEY `idx_transaction` (`transaction_id`),
        KEY `idx_status` (`status`),
        CONSTRAINT `fk_payment_booking` FOREIGN KEY (`booking_id`) REFERENCES `{$wpdb->prefix}babarida_bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_waivers` (
        `waiver_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `booking_id` BIGINT UNSIGNED DEFAULT NULL,
        `customer_id` BIGINT UNSIGNED NOT NULL,
        `waiver_type` ENUM('diving','snorkeling','water_sports','general') NOT NULL DEFAULT 'diving',
        `full_name` VARCHAR(200) NOT NULL,
        `signature_data` LONGTEXT DEFAULT NULL,
        `ip_address` VARCHAR(45) DEFAULT NULL,
        `user_agent` TEXT DEFAULT NULL,
        `pdf_url` VARCHAR(500) DEFAULT NULL,
        `legal_version` VARCHAR(50) DEFAULT '1.0',
        `accepted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`waiver_id`),
        KEY `idx_booking` (`booking_id`),
        KEY `idx_customer` (`customer_id`),
        CONSTRAINT `fk_waiver_customer` FOREIGN KEY (`customer_id`) REFERENCES `{$wpdb->prefix}babarida_customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_memberships` (
        `membership_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `customer_id` BIGINT UNSIGNED NOT NULL,
        `level` ENUM('bronze','silver','gold','platinum','diamond') NOT NULL DEFAULT 'bronze',
        `points_balance` INT UNSIGNED NOT NULL DEFAULT 0,
        `points_earned` INT UNSIGNED NOT NULL DEFAULT 0,
        `points_redeemed` INT UNSIGNED NOT NULL DEFAULT 0,
        `joined_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `renewed_at` DATETIME DEFAULT NULL,
        `expires_at` DATETIME DEFAULT NULL,
        `status` ENUM('active','inactive','expired','suspended') DEFAULT 'active',
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`membership_id`),
        UNIQUE KEY `idx_customer` (`customer_id`),
        CONSTRAINT `fk_membership_customer` FOREIGN KEY (`customer_id`) REFERENCES `{$wpdb->prefix}babarida_customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_loyalty_points` (
        `point_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `customer_id` BIGINT UNSIGNED NOT NULL,
        `booking_id` BIGINT UNSIGNED DEFAULT NULL,
        `points` INT NOT NULL,
        `type` ENUM('earned','redeemed','expired','adjusted','bonus','referral') NOT NULL,
        `description` VARCHAR(500) DEFAULT NULL,
        `balance_after` INT NOT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`point_id`),
        KEY `idx_customer` (`customer_id`),
        KEY `idx_type` (`type`),
        CONSTRAINT `fk_loyalty_customer` FOREIGN KEY (`customer_id`) REFERENCES `{$wpdb->prefix}babarida_customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_notifications` (
        `notif_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` BIGINT UNSIGNED NOT NULL,
        `type` ENUM('booking_created','booking_confirmed','booking_paid','booking_checked_in','booking_completed','booking_cancelled','payment_received','payment_failed','weather_alert','schedule_change','reminder','system','promotion') NOT NULL,
        `channel` ENUM('dashboard','email','whatsapp','push') NOT NULL DEFAULT 'dashboard',
        `title` VARCHAR(255) NOT NULL,
        `message` TEXT NOT NULL,
        `data` JSON DEFAULT NULL,
        `action_url` VARCHAR(500) DEFAULT NULL,
        `is_read` TINYINT(1) NOT NULL DEFAULT 0,
        `sent_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `read_at` DATETIME DEFAULT NULL,
        PRIMARY KEY (`notif_id`),
        KEY `idx_user_read` (`user_id`, `is_read`),
        KEY `idx_type` (`type`),
        CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `{$wpdb->users}` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_chat_messages` (
        `message_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `sender_id` BIGINT UNSIGNED NOT NULL,
        `receiver_id` BIGINT UNSIGNED NOT NULL,
        `booking_id` BIGINT UNSIGNED DEFAULT NULL,
        `message` TEXT NOT NULL,
        `attachment_url` VARCHAR(500) DEFAULT NULL,
        `attachment_name` VARCHAR(255) DEFAULT NULL,
        `is_read` TINYINT(1) NOT NULL DEFAULT 0,
        `is_deleted_for_sender` TINYINT(1) NOT NULL DEFAULT 0,
        `is_deleted_for_receiver` TINYINT(1) NOT NULL DEFAULT 0,
        `sent_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `read_at` DATETIME DEFAULT NULL,
        PRIMARY KEY (`message_id`),
        KEY `idx_conversation` (`sender_id`, `receiver_id`),
        KEY `idx_receiver_read` (`receiver_id`, `is_read`),
        CONSTRAINT `fk_chat_sender` FOREIGN KEY (`sender_id`) REFERENCES `{$wpdb->users}` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `fk_chat_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `{$wpdb->users}` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_activity_logs` (
        `log_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` BIGINT UNSIGNED DEFAULT NULL,
        `action` VARCHAR(100) NOT NULL,
        `resource_type` VARCHAR(50) DEFAULT NULL,
        `resource_id` BIGINT UNSIGNED DEFAULT NULL,
        `details` TEXT DEFAULT NULL,
        `ip_address` VARCHAR(45) DEFAULT NULL,
        `user_agent` TEXT DEFAULT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`log_id`),
        KEY `idx_user` (`user_id`),
        KEY `idx_action` (`action`),
        KEY `idx_resource` (`resource_type`, `resource_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_newsletter_subscribers` (
        `sub_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `email` VARCHAR(255) NOT NULL,
        `first_name` VARCHAR(100) DEFAULT NULL,
        `last_name` VARCHAR(100) DEFAULT NULL,
        `status` ENUM('active','unsubscribed','bounced','complained') NOT NULL DEFAULT 'active',
        `source` VARCHAR(50) DEFAULT 'website',
        `subscribed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `unsubscribed_at` DATETIME DEFAULT NULL,
        `confirmed_at` DATETIME DEFAULT NULL,
        `confirm_token` VARCHAR(64) DEFAULT NULL,
        PRIMARY KEY (`sub_id`),
        UNIQUE KEY `idx_email` (`email`),
        KEY `idx_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_partners` (
        `partner_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` BIGINT UNSIGNED DEFAULT NULL,
        `partner_type` ENUM('hotel','liveaboard','dive_center','restaurant','transport','other') NOT NULL,
        `company_name` VARCHAR(255) NOT NULL,
        `contact_person` VARCHAR(200) DEFAULT NULL,
        `email` VARCHAR(255) DEFAULT NULL,
        `phone` VARCHAR(30) DEFAULT NULL,
        `website_url` VARCHAR(500) DEFAULT NULL,
        `logo_url` VARCHAR(500) DEFAULT NULL,
        `category` VARCHAR(100) DEFAULT NULL,
        `description` TEXT DEFAULT NULL,
        `address` TEXT DEFAULT NULL,
        `city` VARCHAR(100) DEFAULT NULL,
        `province` VARCHAR(100) DEFAULT NULL,
        `country` VARCHAR(100) DEFAULT 'Indonesia',
        `latitude` DECIMAL(10,7) DEFAULT NULL,
        `longitude` DECIMAL(10,7) DEFAULT NULL,
        `commission_rate` DECIMAL(5,2) DEFAULT 0.00,
        `bank_name` VARCHAR(100) DEFAULT NULL,
        `bank_account` VARCHAR(50) DEFAULT NULL,
        `bank_holder` VARCHAR(200) DEFAULT NULL,
        `status` ENUM('pending','approved','rejected','suspended') NOT NULL DEFAULT 'pending',
        `approved_by` BIGINT UNSIGNED DEFAULT NULL,
        `approved_at` DATETIME DEFAULT NULL,
        `rejection_reason` TEXT DEFAULT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`partner_id`),
        KEY `idx_user` (`user_id`),
        KEY `idx_type` (`partner_type`),
        KEY `idx_status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_reviews` (
        `review_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `customer_id` BIGINT UNSIGNED NOT NULL,
        `booking_id` BIGINT UNSIGNED DEFAULT NULL,
        `destination_id` BIGINT UNSIGNED DEFAULT NULL,
        `liveaboard_id` BIGINT UNSIGNED DEFAULT NULL,
        `rating` TINYINT UNSIGNED NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
        `title` VARCHAR(255) DEFAULT NULL,
        `review_text` TEXT DEFAULT NULL,
        `photo_url` VARCHAR(500) DEFAULT NULL,
        `country_code` VARCHAR(3) DEFAULT NULL,
        `country_name` VARCHAR(100) DEFAULT NULL,
        `is_verified` TINYINT(1) NOT NULL DEFAULT 0,
        `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
        `is_anonymous` TINYINT(1) NOT NULL DEFAULT 0,
        `status` ENUM('pending','approved','rejected','hidden') NOT NULL DEFAULT 'pending',
        `admin_reply` TEXT DEFAULT NULL,
        `replied_at` DATETIME DEFAULT NULL,
        `replied_by` BIGINT UNSIGNED DEFAULT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`review_id`),
        KEY `idx_customer` (`customer_id`),
        KEY `idx_booking` (`booking_id`),
        KEY `idx_rating` (`rating`),
        KEY `idx_status` (`status`),
        CONSTRAINT `fk_review_customer` FOREIGN KEY (`customer_id`) REFERENCES `{$wpdb->prefix}babarida_customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_weather_cache` (
        `cache_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `location` VARCHAR(100) NOT NULL,
        `latitude` DECIMAL(10,7) DEFAULT NULL,
        `longitude` DECIMAL(10,7) DEFAULT NULL,
        `data_json` JSON NOT NULL,
        `fetched_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `expires_at` DATETIME NOT NULL,
        PRIMARY KEY (`cache_id`),
        UNIQUE KEY `idx_location` (`location`),
        KEY `idx_expires` (`expires_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};

    CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}babarida_seo_meta` (
        `seo_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        `post_id` BIGINT UNSIGNED DEFAULT NULL,
        `taxonomy_term_id` BIGINT UNSIGNED DEFAULT NULL,
        `meta_title` VARCHAR(255) DEFAULT NULL,
        `meta_description` TEXT DEFAULT NULL,
        `og_title` VARCHAR(255) DEFAULT NULL,
        `og_description` TEXT DEFAULT NULL,
        `og_image` VARCHAR(500) DEFAULT NULL,
        `og_type` VARCHAR(50) DEFAULT 'website',
        `twitter_card` ENUM('summary','summary_large_image','app','player') DEFAULT 'summary_large_image',
        `twitter_title` VARCHAR(255) DEFAULT NULL,
        `twitter_description` TEXT DEFAULT NULL,
        `twitter_image` VARCHAR(500) DEFAULT NULL,
        `canonical_url` VARCHAR(500) DEFAULT NULL,
        `noindex` TINYINT(1) NOT NULL DEFAULT 0,
        `nofollow` TINYINT(1) NOT NULL DEFAULT 0,
        `schema_json` JSON DEFAULT NULL,
        `breadcrumb_json` JSON DEFAULT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`seo_id`),
        UNIQUE KEY `idx_post` (`post_id`),
        UNIQUE KEY `idx_term` (`taxonomy_term_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET={$charset_collate};
    ";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $result = $wpdb->query($sql);

    if ($result === false) {
        error_log('Babarida Core DB Activation Error: ' . $wpdb->last_error);
    }

    // Flush rewrite rules
    flush_rewrite_rules();

    // Add capabilities and roles
    add_babarida_roles();
}

/* ─────────────────────────────────────────────
   PLUGIN DEACTIVATION
   ───────────────────────────────────────────── */
register_deactivation_hook(BABARIDA_CORE_FILE, __NAMESPACE__ . '\\deactivate_babarida_core');

function deactivate_babarida_core(): void
{
    wp_clear_scheduled_hook('babarida_update_weather_cache');
    flush_rewrite_rules();
}

/* ─────────────────────────────────────────────
   CUSTOM USER ROLES & CAPABILITIES
   ───────────────────────────────────────────── */
function add_babarida_roles(): void
{
    // Remove default roles if they exist to refresh capabilities
    remove_role('babarida_general_manager');
    remove_role('babarida_booking_staff');
    remove_role('babarida_dive_guide');
    remove_role('babarida_hotel_partner');
    remove_role('babarida_liveaboard_partner');
    remove_role('babarida_content_editor');
    remove_role('babarida_finance_staff');

    // Base capabilities for standard WordPress access
    $base_caps = array('read');
    
    // Content Capabilities
    $content_caps = array_merge($base_caps, array(
        'edit_posts', 'edit_others_posts', 'edit_private_posts',
        'read_private_posts', 'delete_posts', 'delete_others_posts',
        'delete_private_posts', 'publish_posts', 'upload_files',
    ));

    // Booking Capabilities
    $booking_caps = array_merge($base_caps, array(
        'babarida_view_bookings', 'babarida_create_bookings', 'babarida_edit_bookings',
        'babarida_delete_bookings', 'babarida_manage_availability',
    ));

    // General Manager (Almost full access, except critical network settings)
    add_role('babarida_general_manager', __('General Manager', 'babarida-core'), array_merge($content_caps, $booking_caps, array(
        'babarida_manage_settings', 'babarida_view_reports', 'babarida_manage_pricing',
        'babarida_manage_partners', 'babarida_manage_staff', 'babarida_view_finance',
        'babarida_manage_crm', 'babarida_manage_chat', 'list_users', 'edit_users',
    )));

    // Booking Staff
    add_role('babarida_booking_staff', __('Booking Staff', 'babarida-core'), $booking_caps);

    // Dive Guide
    add_role('babarida_dive_guide', __('Dive Guide', 'babarida-core'), array_merge($base_caps, array(
        'babarida_view_assigned_bookings', 'babarida_submit_reports',
    )));

    // Hotel Partner
    add_role('babarida_hotel_partner', __('Hotel Partner', 'babarida-core'), array_merge($base_caps, array(
        'babarida_view_own_bookings', 'babarida_edit_own_rooms', 'babarida_upload_gallery',
    )));

    // Liveaboard Partner
    add_role('babarida_liveaboard_partner', __('Liveaboard Partner', 'babarida-core'), array_merge($base_caps, array(
        'babarida_view_own_bookings', 'babarida_edit_own_boats', 'babarida_manage_own_availability',
    )));

    // Content Editor
    add_role('babarida_content_editor', __('Content Editor', 'babarida-core'), array_merge($content_caps, array(
        'edit_pages', 'edit_others_pages', 'publish_pages',
    )));

    // Finance Staff
    add_role('babarida_finance_staff', __('Finance Staff', 'babarida-core'), array_merge($base_caps, array(
        'babarida_view_finance', 'babarida_manage_payments', 'babarida_view_invoices',
        'babarida_process_refunds',
    )));
}

/* ─────────────────────────────────────────────
   ADMIN INITIALIZATION
   ───────────────────────────────────────────── */
add_action('admin_init', __NAMESPACE__ . '\\admin_init_handler');

function admin_init_handler(): void
{
    // Redirect non-admins away from pure admin pages if needed
    $current_user = wp_get_current_user();
    if (in_array('babarida_dive_guide', $current_user->roles) && !defined('DOING_AJAX')) {
        $redirect_to = admin_url('index.php'); // Dashboard only
        $current_page = basename($_SERVER['PHP_SELF']);
        if ($current_page !== 'index.php' && $current_page !== 'profile.php' && $current_page !== 'admin-ajax.php') {
            wp_redirect($redirect_to);
            exit;
        }
    }
}

/* ─────────────────────────────────────────────
   ADMIN MENUS
   ───────────────────────────────────────────── */
add_action('admin_menu', __NAMESPACE__ . '\\register_admin_menus');

function register_admin_menus(): void
{
    // Main Dashboard
    add_menu_page(
        __('Babarida Dashboard', 'babarida-core'),
        __('Babarida', 'babarida-core'),
        'babarida_view_bookings',
        'babarida-dashboard',
        __NAMESPACE__ . '\\render_dashboard_page',
        'dashicons-sailboat',
        2
    );

    // Bookings Submenu
    add_submenu_page(
        'babarida-dashboard',
        __('All Bookings', 'babarida-core'),
        __('Bookings', 'babarida-core'),
        'babarida_view_bookings',
        'babarida-bookings',
        __NAMESPACE__ . '\\render_bookings_page'
    );

    // CRM Submenu
    add_submenu_page(
        'babarida-dashboard',
        __('Customer CRM', 'babarida-core'),
        __('CRM', 'babarida-core'),
        'babarida_manage_crm',
        'babarida-crm',
        __NAMESPACE__ . '\\render_crm_page'
    );

    // Liveaboards Submenu (Custom Post Type override)
    add_submenu_page(
        'babarida-dashboard',
        __('Liveaboard Management', 'babarida-core'),
        __('Liveaboards', 'babarida-core'),
        'edit_posts',
        'edit.php?post_type=liveaboard'
    );

    // Dynamic Pricing Submenu
    add_submenu_page(
        'babarida-dashboard',
        __('Dynamic Pricing', 'babarida-core'),
        __('Pricing', 'babarida-core'),
        'babarida_manage_pricing',
        'babarida-pricing',
        __NAMESPACE__ . '\\render_pricing_page'
    );

    // Partners Submenu
    add_submenu_page(
        'babarida-dashboard',
        __('Partners', 'babarida-core'),
        __('Partners', 'babarida-core'),
        'babarida_manage_partners',
        'babarida-partners',
        __NAMESPACE__ . '\\render_partners_page'
    );

    // Internal Chat Submenu
    add_submenu_page(
        'babarida-dashboard',
        __('Internal Chat', 'babarida-core'),
        __('Chat', 'babarida-core'),
        'babarida_manage_chat',
        'babarida-chat',
        __NAMESPACE__ . '\\render_chat_page'
    );

    // System Health Submenu
    add_submenu_page(
        'babarida-dashboard',
        __('System Health', 'babarida-core'),
        __('System Health', 'babarida-core'),
        'babarida_manage_settings',
        'babarida-health',
        __NAMESPACE__ . '\\render_health_page'
    );
}

/* ─────────────────────────────────────────────
   ADMIN DASHBOARD WIDGETS
   ───────────────────────────────────────────── */
add_action('wp_dashboard_setup', __NAMESPACE__ . '\\register_dashboard_widgets');

function register_dashboard_widgets(): void
{
    if (!current_user_can('babarida_view_bookings')) {
        return;
    }

    wp_add_dashboard_widget(
        'babarida_revenue_widget',
        __('Revenue Overview', 'babarida-core'),
        __NAMESPACE__ . '\\render_revenue_widget'
    );

    wp_add_dashboard_widget(
        'babarida_recent_bookings_widget',
        __('Recent Bookings', 'babarida-core'),
        __NAMESPACE__ . '\\render_recent_bookings_widget'
    );

    wp_add_dashboard_widget(
        'babarida_occupancy_widget',
        __('Occupancy & Availability', 'babarida-core'),
        __NAMESPACE__ . '\\render_occupancy_widget'
    );
}

function render_revenue_widget(): void
{
    global $wpdb;
    $table = $wpdb->prefix . 'babarida_payments';
    
    // Total Revenue
    $total_revenue = $wpdb->get_var("SELECT COALESCE(SUM(amount), 0) FROM {$table} WHERE status = 'completed'");
    // Monthly Revenue
    $monthly_revenue = $wpdb->get_var($wpdb->prepare(
        "SELECT COALESCE(SUM(amount), 0) FROM {$table} WHERE status = 'completed' AND paid_at >= %s",
        gmdate('Y-m-01 00:00:00')
    ));
    // Pending Payments
    $pending_payments = $wpdb->get_var("SELECT COUNT(*) FROM {$table} WHERE status = 'pending'");
    
    $currency = 'USD';
    ?>
    <div class="babarida-widget-grid" style="display:grid;gap:16px;">
        <div class="babarida-stat-card" style="background:#f0fdf4;padding:16px;border-radius:8px;border-left:4px solid #22c55e;">
            <span style="font-size:12px;color:#64748B;text-transform:uppercase;letter-spacing:1px;">Total Revenue</span>
            <p style="font-size:24px;font-weight:700;color:#0F172A;margin:4px 0 0;"><?php echo esc_html(babarida_core_format_money((float)$total_revenue, $currency)); ?></p>
        </div>
        <div class="babarida-stat-card" style="background:#eff6ff;padding:16px;border-radius:8px;border-left:4px solid #00BFFF;">
            <span style="font-size:12px;color:#64748B;text-transform:uppercase;letter-spacing:1px;">This Month</span>
            <p style="font-size:24px;font-weight:700;color:#0F172A;margin:4px 0 0;"><?php echo esc_html(babarida_core_format_money((float)$monthly_revenue, $currency)); ?></p>
        </div>
        <div class="babarida-stat-card" style="background:#fefce8;padding:16px;border-radius:8px;border-left:4px solid #FACC15;">
            <span style="font-size:12px;color:#64748B;text-transform:uppercase;letter-spacing:1px;">Pending Payments</span>
            <p style="font-size:24px;font-weight:700;color:#0F172A;margin:4px 0 0;"><?php echo esc_html((int)$pending_payments); ?></p>
        </div>
    </div>
    <?php
}

function render_recent_bookings_widget(): void
{
    global $wpdb;
    $bookings_table = $wpdb->prefix . 'babarida_bookings';
    $customers_table = $wpdb->prefix . 'babarida_customers';
    
    $recent = $wpdb->get_results(
        "SELECT b.booking_code, b.trip_type, b.total_price, b.status, b.created_at,
                CONCAT(c.first_name, ' ', c.last_name) AS customer_name
         FROM {$bookings_table} b
         LEFT JOIN {$customers_table} c ON b.customer_id = c.customer_id
         ORDER BY b.created_at DESC LIMIT 5"
    );

    if (empty($recent)) {
        echo '<p>' . esc_html__('No bookings yet.', 'babarida-core') . '</p>';
        return;
    }
    ?>
    <ul style="margin:0;list-style:none;">
        <?php foreach ($recent as $b) : ?>
            <li style="padding:10px 0;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <strong style="color:#0F172A;"><?php echo esc_html($b->customer_name); ?></strong><br>
                    <span style="font-size:12px;color:#64748B;"><?php echo esc_html($b->booking_code); ?> &middot; <?php echo esc_html(ucfirst($b->trip_type)); ?></span>
                </div>
                <div style="text-align:right;">
                    <span style="font-weight:600;color:#0F172A;"><?php echo esc_html(babarida_core_format_money((float)$b->total_price, 'USD')); ?></span><br>
                    <span class="status-badge status-<?php echo esc_attr($b->status); ?>" style="font-size:10px;padding:2px 8px;"><?php echo esc_html(ucfirst($b->status)); ?></span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <style>
        .status-badge { display:inline-block; border-radius:9999px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px; }
        .status-pending { background:#FEF3C7; color:#92400E; }
        .status-confirmed { background:#DBEAFE; color:#1E40AF; }
        .status-paid { background:#D1FAE5; color:#065F46; }
        .status-cancelled { background:#FEE2E2; color:#991B1B; }
    </style>
    <?php
}

function render_occupancy_widget(): void
{
    global $wpdb;
    $avail_table = $wpdb->prefix . 'babarida_availability';
    $today = gmdate('Y-m-d');
    $next_week = gmdate('Y-m-d', strtotime('+7 days'));

    // Average occupancy this week
    $stats = $wpdb->get_row($wpdb->prepare(
        "SELECT 
            COUNT(*) as total_days,
            SUM(CASE WHEN total_slots > 0 THEN (booked_slots / total_slots) * 100 ELSE 0 END) / COUNT(*) as avg_occupancy
         FROM {$avail_table} 
         WHERE date BETWEEN %s AND %s AND total_slots > 0",
        $today, $next_week
    ));

    $occupancy = $stats ? round((float)$stats->avg_occupancy, 1) : 0;
    $color = $occupancy > 80 ? '#ef4444' : ($occupancy > 50 ? '#FACC15' : '#22c55e');
    ?>
    <div style="text-align:center;padding:10px 0;">
        <div style="position:relative;width:120px;height:120px;margin:0 auto 16px;">
            <svg viewBox="0 0 36 36" style="width:100%;height:100%;transform:rotate(-90deg);">
                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e2e8f0" stroke-width="3"/>
                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="<?php echo esc_attr($color); ?>" stroke-width="3" stroke-dasharray="<?php echo esc_attr($occupancy); ?>, 100"/>
            </svg>
            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;color:#0F172A;">
                <?php echo esc_html($occupancy); ?>%
            </div>
        </div>
        <p style="font-size:13px;color:#64748B;"><?php esc_html_e('Average Occupancy (Next 7 Days)', 'babarida-core'); ?></p>
    </div>
    <?php
}

/* ─────────────────────────────────────────────
   ADMIN PAGE RENDERERS (Stubs for full UI)
   ───────────────────────────────────────────── */
function render_dashboard_page(): void
{
    echo '<div class="wrap"><h1>' . esc_html__('Babarida Dive Center Dashboard', 'babarida-core') . '</h1><p>' . esc_html__('Welcome to the enterprise management system.', 'babarida-core') . '</p></div>';
}
function render_bookings_page(): void
{
    echo '<div class="wrap"><h1>' . esc_html__('Bookings Management', 'babarida-core') . '</h1></div>';
}
function render_crm_page(): void
{
    echo '<div class="wrap"><h1>' . esc_html__('Customer Relationship Management', 'babarida-core') . '</h1></div>';
}
function render_pricing_page(): void
{
    echo '<div class="wrap"><h1>' . esc_html__('Dynamic Pricing Engine', 'babarida-core') . '</h1></div>';
}
function render_partners_page(): void
{
    echo '<div class="wrap"><h1>' . esc_html__('Partner Management', 'babarida-core') . '</h1></div>';
}
function render_chat_page(): void
{
    echo '<div class="wrap"><h1>' . esc_html__('Internal Communication', 'babarida-core') . '</h1></div>';
}
function render_health_page(): void
{
    echo '<div class="wrap"><h1>' . esc_html__('System Health Monitor', 'babarida-core') . '</h1></div>';
}

/* ─────────────────────────────────────────────
   HELPER: Format Money
   ───────────────────────────────────────────── */
function babarida_core_format_money(float $amount, string $currency = 'USD'): string
{
    $symbols = array('USD' => '$', 'EUR' => '€', 'GBP' => '£', 'IDR' => 'Rp', 'SGD' => 'S$', 'AUD' => 'A$');
    $symbol = $symbols[$currency] ?? $currency;
    
    if ($currency === 'IDR') {
        return $symbol . ' ' . number_format($amount, 0, ',', '.');
    }
    return $symbol . number_format($amount, 2, '.', ',');
}

/* ─────────────────────────────────────────────
   ADMIN ASSETS
   ───────────────────────────────────────────── */
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_assets');

function enqueue_admin_assets(string $hook): void
{
    if (strpos($hook, 'babarida') === false) {
        return;
    }
    
    wp_enqueue_style(
        'babarida-core-admin',
        BABARIDA_CORE_URI . 'assets/css/admin.css',
        array(),
        BABARIDA_CORE_VERSION
    );

    wp_enqueue_script(
        'babarida-core-admin',
        BABARIDA_CORE_URI . 'assets/js/admin.js',
        array('jquery'),
        BABARIDA_CORE_VERSION,
        true
    );

    wp_localize_script('babarida-core-admin', 'babaridaAdmin', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('babarida_admin_nonce'),
    ));
}
