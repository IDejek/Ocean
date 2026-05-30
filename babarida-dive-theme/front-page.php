<?php
/**
 * Babarida Dive Center - Front Page Template
 * Cinematic luxury landing page
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();

 $hero_video    = get_theme_mod('babarida_hero_video_url', '');
 $hero_image    = get_theme_mod('babarida_hero_image_url', get_template_directory_uri() . '/assets/images/hero-bunaken-reef.jpg');
 $hero_subtitle = get_theme_mod('babarida_hero_subtitle', 'The quality of your dive adventure depends on who guides you!');
 $hero_cta_text = get_theme_mod('babarida_hero_cta_text', 'Book Your Dive');
 $hero_cta_url  = get_theme_mod('babarida_hero_cta_url', '/check-in/');
 $hero_type     = get_theme_mod('babarida_hero_type', 'image');
?>

<!-- Preloader -->
<div id="babarida-preloader" aria-hidden="true">
    <div class="preloader-inner">
        <div class="preloader-wave">
            <svg class="wave-svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path class="wave-path wave-1" d="M0,160L48,170.7C96,181,192,203,288,197.3C384,192,480,160,576,149.3C672,139,768,149,864,170.7C960,192,1056,224,1152,218.7C1248,213,1344,171,1392,149.3L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/>
                <path class="wave-path wave-2" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,213.3C672,224,768,224,864,208C960,192,1056,160,1152,165.3C1248,171,1344,213,1392,234.7L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/>
                <path class="wave-path wave-3" d="M0,256L48,261.3C96,267,192,277,288,272C384,267,480,245,576,240C672,235,768,245,864,256C960,267,1056,277,1152,272C1248,267,1344,245,1392,234.7L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"/>
            </svg>
        </div>
        <div class="preloader-logo">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/babarida-logo-white.svg'); ?>" alt="Babarida Dive Center" width="200" height="60">
        </div>
        <div class="preloader-bar">
            <div class="preloader-bar-fill"></div>
        </div>
    </div>
</div>

<!-- Hero Section -->
<section id="hero-section" class="hero-section hero-fullscreen" aria-label="Hero">
    <?php if ('video' === $hero_type && !empty($hero_video)) : ?>
        <div class="hero-video-wrapper">
            <video class="hero-video" autoplay muted loop playsinline preload="auto" poster="<?php echo esc_url($hero_image); ?>">
                <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
            </video>
            <div class="hero-video-overlay"></div>
        </div>
    <?php else : ?>
        <div class="hero-image-wrapper">
            <img src="<?php echo esc_url($hero_image); ?>" alt="Bunaken Coral Reef - Babarida Dive Center" class="hero-image" fetchpriority="high">
        </div>
    <?php endif; ?>

    <div class="hero-content">
        <div class="hero-content-inner">
            <h1 class="hero-title" data-animate="fade-up" data-delay="0">
                <span class="hero-title-line">Babarida Dive</span>
                <span class="hero-title-line hero-title-accent">Center</span>
            </h1>
            <p class="hero-subtitle" data-animate="fade-up" data-delay="200">
                <?php echo esc_html($hero_subtitle); ?>
            </p>
            <div class="hero-cta-group" data-animate="fade-up" data-delay="400">
                <a href="<?php echo esc_url($hero_cta_url); ?>" class="btn btn-primary btn-magnetic btn-lg">
                    <span class="btn-text"><?php echo esc_html($hero_cta_text); ?></span>
                    <span class="btn-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                </a>
                <a href="#destinations" class="btn btn-outline-white btn-magnetic btn-lg">
                    <span class="btn-text"><?php esc_html_e('Explore Destinations', 'babarida-dive'); ?></span>
                </a>
            </div>
        </div>
    </div>

    <div class="hero-scroll-indicator" data-animate="fade-in" data-delay="800">
        <span class="scroll-text"><?php esc_html_e('Scroll', 'babarida-dive'); ?></span>
        <div class="scroll-line">
            <div class="scroll-line-dot"></div>
        </div>
    </div>

    <!-- Floating Bubbles -->
    <div class="hero-bubbles" aria-hidden="true">
        <?php for ($i = 1; $i <= 12; $i++) : ?>
            <div class="bubble bubble-<?php echo esc_attr($i); ?>" style="--bubble-size: <?php echo esc_attr(rand(8, 40)); ?>px; --bubble-left: <?php echo esc_attr(rand(5, 95)); ?>%; --bubble-delay: <?php echo esc_attr(rand(0, 8)); ?>s; --bubble-duration: <?php echo esc_attr(rand(6, 14)); ?>s;"></div>
        <?php endfor; ?>
    </div>
</section>

<!-- Welcome Section -->
<section id="welcome-section" class="welcome-section section-padding" aria-label="<?php esc_attr_e('Welcome', 'babarida-dive'); ?>">
    <div class="container">
        <div class="section-header" data-animate="fade-up">
            <span class="section-label"><?php esc_html_e('Welcome', 'babarida-dive'); ?></span>
            <h2 class="section-title"><?php esc_html_e('Welcome to Babarida Dive Center', 'babarida-dive'); ?></h2>
            <p class="section-subtitle">"Unchain Your Adventure, Go Scuba Diving!"</p>
        </div>
        <div class="welcome-grid">
            <div class="welcome-content" data-animate="fade-up" data-delay="100">
                <p class="welcome-text">
                    <?php esc_html_e('Our team is intimately familiar with Bunaken, Siladen, Bangka, and Lembeh and has worked together for years, creating safe, smooth, and unforgettable experiences for divers of all levels.', 'babarida-dive'); ?>
                </p>
                <p class="welcome-text">
                    <?php esc_html_e('From the vibrant walls of Bunaken to the world-renowned muck diving of Lembeh Strait, every dive with us is a carefully curated experience designed to exceed expectations.', 'babarida-dive'); ?>
                </p>
            </div>
            <div class="welcome-services" data-animate="fade-up" data-delay="200">
                <div class="service-cards-grid">
                    <?php
                     $services = array(
    array('icon' => 'ship',     'label' => 'Liveaboard Cruises'), // <- TAMBAHKAN =>
    array('icon' => 'compass',  'label' => 'Dive Safaris'),
                        array('icon' => 'waves',    'label' => 'Water Sports'),
                        array('icon' => 'sun',      'label' => 'Day Trips'),
                        array('icon' => 'award',    'label' => 'SSI Courses'),
                    );
                    foreach ($services as $service) :
                        ?>
                        <div class="service-card">
                            <div class="service-card-icon">
                                <?php babarida_get_icon($service['icon']); ?>
                            </div>
                            <h3 class="service-card-label"><?php echo esc_html($service['label']); ?></h3>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Destinations Section -->
<section id="destinations" class="destinations-section section-padding section-dark" aria-label="<?php esc_attr_e('Destinations', 'babarida-dive'); ?>">
    <div class="container">
        <div class="section-header" data-animate="fade-up">
            <span class="section-label"><?php esc_html_e('Destinations', 'babarida-dive'); ?></span>
            <h2 class="section-title"><?php esc_html_e('Explore Our Dive Paradises', 'babarida-dive'); ?></h2>
            <p class="section-subtitle"><?php esc_html_e('Four world-class destinations, one extraordinary experience', 'babarida-dive'); ?></p>
        </div>
        <div class="destinations-grid">
            <?php
            $destinations = babarida_get_destinations();
            if ($destinations->have_posts()) :
                $delay = 0;
                while ($destinations->have_posts()) :
                    $destinations->the_post();
                    $dest_id    = get_the_ID();
                    $thumbnail  = get_the_post_thumbnail_url($dest_id, 'destinations-card');
                    $subtitle   = get_post_meta($dest_id, '_babarida_destination_subtitle', true);
                    $dives      = get_post_meta($dest_id, '_babarida_destination_dive_sites', true);
                    $difficulty = get_post_meta($dest_id, '_babarida_destination_difficulty', true);
                    if (!$thumbnail) {
                        $thumbnail = get_template_directory_uri() . '/assets/images/destination-default.jpg';
                    }
                    ?>
                    <article class="destination-card" data-animate="fade-up" data-delay="<?php echo esc_attr($delay); ?>">
                        <a href="<?php the_permalink(); ?>" class="destination-card-link" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                            <div class="destination-card-image">
                                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" width="600" height="400">
                                <div class="destination-card-overlay"></div>
                                <?php if ($difficulty) : ?>
                                    <span class="destination-badge"><?php echo esc_html($difficulty); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="destination-card-content">
                                <h3 class="destination-card-title"><?php the_title(); ?></h3>
                                <?php if ($subtitle) : ?>
                                    <p class="destination-card-subtitle"><?php echo esc_html($subtitle); ?></p>
                                <?php endif; ?>
                                <?php if ($dives) : ?>
                                    <span class="destination-card-stats"><?php echo esc_html($dives); ?> <?php esc_html_e('Dive Sites', 'babarida-dive'); ?></span>
                                <?php endif; ?>
                                <span class="destination-card-arrow">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </a>
                    </article>
                    <?php
                    $delay += 150;
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<!-- Liveaboards Preview -->
<section id="liveaboards-preview" class="liveaboards-section section-padding" aria-label="<?php esc_attr_e('Liveaboards', 'babarida-dive'); ?>">
    <div class="container">
        <div class="section-header" data-animate="fade-up">
            <span class="section-label"><?php esc_html_e('Liveaboards', 'babarida-dive'); ?></span>
            <h2 class="section-title"><?php esc_html_e('Luxury Liveaboard Experiences', 'babarida-dive'); ?></h2>
            <p class="section-subtitle"><?php esc_html_e('Sleep above the ocean, wake up to world-class dive sites', 'babarida-dive'); ?></p>
        </div>
        <div class="liveaboards-grid">
            <?php
            $liveaboards = new WP_Query(
                array(
                    'post_type'      => 'liveaboard',
                    'posts_per_page' => 3,
                    'post_status'    => 'publish',
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                )
            );
            if ($liveaboards->have_posts()) :
                $delay = 0;
                while ($liveaboards->have_posts()) :
                    $liveaboards->the_post();
                    $lb_id       = get_the_ID();
                    $thumbnail   = get_the_post_thumbnail_url($lb_id, 'liveaboard-card');
                    $length      = get_post_meta($lb_id, '_babarida_liveaboard_length', true);
                    $guests      = get_post_meta($lb_id, '_babarida_liveaboard_guests', true);
                    $price_from  = get_post_meta($lb_id, '_babarida_liveaboard_price_from', true);
                    $cabins      = get_post_meta($lb_id, '_babarida_liveaboard_cabins', true);
                    if (!$thumbnail) {
                        $thumbnail = get_template_directory_uri() . '/assets/images/liveaboard-default.jpg';
                    }
                    ?>
                    <article class="liveaboard-card" data-animate="fade-up" data-delay="<?php echo esc_attr($delay); ?>">
                        <a href="<?php the_permalink(); ?>" class="liveaboard-card-link">
                            <div class="liveaboard-card-image">
                                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" width="600" height="400">
                                <div class="liveaboard-card-overlay"></div>
                            </div>
                            <div class="liveaboard-card-body">
                                <h3 class="liveaboard-card-title"><?php the_title(); ?></h3>
                                <div class="liveaboard-card-specs">
                                    <?php if ($length) : ?>
                                        <span class="spec-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12h5l3-9 4 18 3-9h5"/></svg>
                                            <?php echo esc_html($length); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($guests) : ?>
                                        <span class="spec-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                            <?php echo esc_html($guests); ?> <?php esc_html_e('Guests', 'babarida-dive'); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($cabins) : ?>
                                        <span class="spec-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/></svg>
                                            <?php echo esc_html($cabins); ?> <?php esc_html_e('Cabins', 'babarida-dive'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($price_from) : ?>
                                    <div class="liveaboard-card-price">
                                        <span class="price-from"><?php esc_html_e('From', 'babarida-dive'); ?></span>
                                        <span class="price-amount"><?php echo esc_html($price_from); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>
                    </article>
                    <?php
                    $delay += 150;
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
        <div class="section-cta" data-animate="fade-up">
            <a href="<?php echo esc_url(get_post_type_archive_link('liveaboard')); ?>" class="btn btn-primary btn-magnetic btn-lg">
                <span class="btn-text"><?php esc_html_e('View All Liveaboards', 'babarida-dive'); ?></span>
                <span class="btn-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
            </a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials-section" class="testimonials-section section-padding section-dark" aria-label="<?php esc_attr_e('Testimonials', 'babarida-dive'); ?>">
    <div class="container">
        <div class="section-header" data-animate="fade-up">
            <span class="section-label"><?php esc_html_e('Testimonials', 'babarida-dive'); ?></span>
            <h2 class="section-title"><?php esc_html_e('What Our Divers Say', 'babarida-dive'); ?></h2>
        </div>
        <div class="testimonials-slider" data-animate="fade-up" data-delay="100">
            <div class="testimonials-track">
                <?php
                $testimonials = babarida_get_testimonials(6);
                foreach ($testimonials as $testimonial) :
                    $rating = intval($testimonial->rating);
                    $stars  = '';
                    for ($s = 1; $s <= 5; $s++) {
                        $stars .= ($s <= $rating) ? '&#9733;' : '&#9734;';
                    }
                    ?>
                    <div class="testimonial-card">
                        <div class="testimonial-card-inner">
                            <div class="testimonial-stars" aria-label="<?php echo esc_attr($rating . ' out of 5 stars'); ?>">
                                <?php echo $stars; ?>
                            </div>
                            <?php if (!empty($testimonial->review_text)) : ?>
                                <blockquote class="testimonial-text">"<?php echo esc_html($testimonial->review_text); ?>"</blockquote>
                            <?php endif; ?>
                            <?php if (!empty($testimonial->title)) : ?>
                                <cite class="testimonial-title"><?php echo esc_html($testimonial->title); ?></cite>
                            <?php endif; ?>
                            <div class="testimonial-author">
                                <?php if (!empty($testimonial->photo_url)) : ?>
                                    <img src="<?php echo esc_url($testimonial->photo_url); ?>" alt="<?php echo esc_attr($testimonial->full_name ?? ''); ?>" class="testimonial-avatar" loading="lazy" width="56" height="56">
                                <?php else : ?>
                                    <div class="testimonial-avatar-placeholder">
                                        <?php echo esc_html(mb_substr($testimonial->full_name ?? 'D', 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="testimonial-author-info">
                                    <span class="testimonial-name"><?php echo esc_html($testimonial->full_name ?? ''); ?></span>
                                    <?php if (!empty($testimonial->country_code)) : ?>
                                        <span class="testimonial-country">
                                            <img src="<?php echo esc_url('https://flagcdn.com/w40/' . strtolower($testimonial->country_code) . '.png'); ?>" alt="" width="20" height="14" loading="lazy">
                                            <?php echo esc_html($testimonial->country_name ?? ''); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="testimonials-nav">
                <button class="testimonial-nav-btn testimonial-prev" aria-label="<?php esc_attr_e('Previous testimonial', 'babarida-dive'); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                </button>
                <div class="testimonials-dots"></div>
                <button class="testimonial-nav-btn testimonial-next" aria-label="<?php esc_attr_e('Next testimonial', 'babarida-dive'); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Weather Widget -->
<section id="weather-section" class="weather-section section-padding" aria-label="<?php esc_attr_e('Marine Weather', 'babarida-dive'); ?>">
    <div class="container">
        <div class="section-header" data-animate="fade-up">
            <span class="section-label"><?php esc_html_e('Live Conditions', 'babarida-dive'); ?></span>
            <h2 class="section-title"><?php esc_html_e('Current Marine Weather', 'babarida-dive'); ?></h2>
        </div>
        <div class="weather-grid" id="weather-grid" data-animate="fade-up" data-delay="100">
            <div class="weather-loading">
                <div class="spinner"></div>
                <p><?php esc_html_e('Fetching live marine conditions...', 'babarida-dive'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Partners Section -->
<section id="partners-section" class="partners-section section-padding section-dark" aria-label="<?php esc_attr_e('Partners', 'babarida-dive'); ?>">
    <div class="container">
        <div class="section-header" data-animate="fade-up">
            <span class="section-label"><?php esc_html_e('Partners', 'babarida-dive'); ?></span>
            <h2 class="section-title"><?php esc_html_e('Our Trusted Partners', 'babarida-dive'); ?></h2>
        </div>
        <div class="partners-carousel" data-animate="fade-up" data-delay="100">
            <div class="partners-track" id="partners-track">
                <?php
                $partners = babarida_get_approved_partners();
                foreach ($partners as $partner) :
                    ?>
                    <div class="partner-card">
                        <?php if (!empty($partner->logo_url)) : ?>
                            <img src="<?php echo esc_url($partner->logo_url); ?>" alt="<?php echo esc_attr($partner->company_name); ?>" class="partner-logo" loading="lazy" width="160" height="80">
                        <?php else : ?>
                            <span class="partner-name"><?php echo esc_html($partner->company_name); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($partner->website_url)) : ?>
                            <a href="<?php echo esc_url($partner->website_url); ?>" target="_blank" rel="noopener noreferrer" class="partner-link" aria-label="<?php echo esc_attr(sprintf(__('Visit %s', 'babarida-dive'), $partner->company_name)); ?>">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section id="newsletter-section" class="newsletter-section" aria-label="<?php esc_attr_e('Newsletter', 'babarida-dive'); ?>">
    <div class="newsletter-bg">
        <div class="newsletter-bubbles" aria-hidden="true">
            <?php for ($i = 1; $i <= 8; $i++) : ?>
                <div class="bubble bubble-<?php echo esc_attr($i); ?>" style="--bubble-size: <?php echo esc_attr(rand(6, 30)); ?>px; --bubble-left: <?php echo esc_attr(rand(5, 95)); ?>%; --bubble-delay: <?php echo esc_attr(rand(0, 6)); ?>s; --bubble-duration: <?php echo esc_attr(rand(8, 16)); ?>s;"></div>
            <?php endfor; ?>
        </div>
    </div>
    <div class="container">
        <div class="newsletter-inner" data-animate="fade-up">
            <h2 class="newsletter-title"><?php esc_html_e('Stay Updated', 'babarida-dive'); ?></h2>
            <p class="newsletter-text"><?php esc_html_e('Subscribe for exclusive dive deals, new destinations, and marine life updates.', 'babarida-dive'); ?></p>
            <form id="newsletter-form" class="newsletter-form" method="post" novalidate>
                <?php wp_nonce_field('babarida_newsletter_subscribe', 'newsletter_nonce'); ?>
                <div class="newsletter-form-row">
                    <div class="newsletter-field">
                        <input type="text" name="newsletter_first_name" id="newsletter-first-name" placeholder="<?php esc_attr_e('First Name', 'babarida-dive'); ?>" autocomplete="given-name" required>
                    </div>
                    <div class="newsletter-field">
                        <input type="email" name="newsletter_email" id="newsletter-email" placeholder="<?php esc_attr_e('Email Address', 'babarida-dive'); ?>" autocomplete="email" required>
                    </div>
                    <div class="newsletter-submit">
                        <button type="submit" class="btn btn-tropical btn-magnetic">
                            <span class="btn-text"><?php esc_html_e('Subscribe', 'babarida-dive'); ?></span>
                            <span class="btn-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
                        </button>
                    </div>
                </div>
                <div class="newsletter-message" id="newsletter-message" role="alert" aria-live="polite"></div>
            </form>
        </div>
    </div>
</section>

<?php get_footer(); ?>
