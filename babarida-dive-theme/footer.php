<?php
/**
 * Babarida Dive Center - Footer Template
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

 $whatsapp_number = '62895801960359';
 $whatsapp_link   = 'https://wa.me/' . $whatsapp_number;
 $email_address   = 'info@babaridadive.com';
 $phone_number    = '+62 895 8019 60359';
 $current_year    = gmdate('Y');
?>

<footer class="site-footer" role="contentinfo">
    <!-- Footer Main -->
    <div class="footer-main section-padding">
        <div class="container">
            <div class="footer-grid">
                <!-- Footer Brand -->
                <div class="footer-col footer-brand-col">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo" aria-label="<?php esc_attr_e('Babarida Dive Center', 'babarida-dive'); ?>">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/babarida-logo-white.svg'); ?>" alt="Babarida Dive Center" width="180" height="50" loading="lazy">
                    </a>
                    <p class="footer-brand-text">
                        <?php esc_html_e('Premium diving experiences in the heart of North Sulawesi\'s Coral Triangle. Bunaken, Siladen, Bangka, and Lembeh.', 'babarida-dive'); ?>
                    </p>
                    <div class="footer-social">
                        <a href="https://www.instagram.com/babaridadive/" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="<?php esc_attr_e('Instagram', 'babarida-dive'); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                        <a href="https://www.facebook.com/babaridadive/" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="<?php esc_attr_e('Facebook', 'babarida-dive'); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="https://www.tiktok.com/@babaridadive/" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="<?php esc_attr_e('TikTok', 'babarida-dive'); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 0 0-.79-.05A6.34 6.34 0 0 0 3.15 15a6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V8.35a8.16 8.16 0 0 0 4.76 1.52V6.43a4.85 4.85 0 0 1-1-.26z"/></svg>
                        </a>
                        <a href="https://www.youtube.com/@babaridadive/" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="<?php esc_attr_e('YouTube', 'babarida-dive'); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.1c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Destinations -->
                <div class="footer-col">
                    <h3 class="footer-col-title"><?php esc_html_e('Destinations', 'babarida-dive'); ?></h3>
                    <ul class="footer-links">
                        <li><a href="<?php echo esc_url(home_url('/destinations/bunaken/')); ?>"><?php esc_html_e('Bunaken', 'babarida-dive'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/destinations/siladen/')); ?>"><?php esc_html_e('Siladen', 'babarida-dive'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/destinations/bangka/')); ?>"><?php esc_html_e('Bangka', 'babarida-dive'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/destinations/lembeh/')); ?>"><?php esc_html_e('Lembeh', 'babarida-dive'); ?></a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div class="footer-col">
                    <h3 class="footer-col-title"><?php esc_html_e('Services', 'babarida-dive'); ?></h3>
                    <ul class="footer-links">
                        <li><a href="<?php echo esc_url(get_post_type_archive_link('liveaboard')); ?>"><?php esc_html_e('Liveaboards', 'babarida-dive'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/dive-safaris/')); ?>"><?php esc_html_e('Dive Safaris', 'babarida-dive'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/water-sports/')); ?>"><?php esc_html_e('Water Sports', 'babarida-dive'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/ssi-courses/')); ?>"><?php esc_html_e('SSI Courses', 'babarida-dive'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/day-trips/')); ?>"><?php esc_html_e('Day Trips', 'babarida-dive'); ?></a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="footer-col">
                    <h3 class="footer-col-title"><?php esc_html_e('Contact', 'babarida-dive'); ?></h3>
                    <ul class="footer-contact">
                        <li class="contact-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>Bunaken, Manado, North Sulawesi, Indonesia</span>
                        </li>
                        <li class="contact-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone_number)); ?>"><?php echo esc_html($phone_number); ?></a>
                        </li>
                        <li class="contact-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <a href="mailto:<?php echo esc_attr($email_address); ?>"><?php echo esc_html($email_address); ?></a>
                        </li>
                        <li class="contact-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            <a href="<?php echo esc_url($whatsapp_link); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Chat WhatsApp', 'babarida-dive'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p class="footer-copyright">
                &copy; <?php echo esc_html($current_year); ?> <?php esc_html_e('Babarida Dive Center. All rights reserved.', 'babarida-dive'); ?>
                <span class="footer-dev"><?php printf(esc_html__('Developed by %s', 'babarida-dive'), '<a href="https://babaridadive.com" rel="noopener noreferrer">Iqbal Tombinawa</a>'); ?></span>
            </p>
            <div class="footer-bottom-links">
                <a href="<?php echo esc_url(get_privacy_policy_url()); ?>"><?php esc_html_e('Privacy Policy', 'babarida-dive'); ?></a>
                <a href="<?php echo esc_url(home_url('/terms-conditions/')); ?>"><?php esc_html_e('Terms & Conditions', 'babarida-dive'); ?></a>
                <a href="<?php echo esc_url(home_url('/cancellation-policy/')); ?>"><?php esc_html_e('Cancellation Policy', 'babarida-dive'); ?></a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
