<?php
/**
 * Asset Enqueue Class
 *
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

namespace Babarida_Theme;

if (!defined('ABSPATH')) {
    exit;
}

class Babarida_Assets
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('enqueue_block_editor_assets', [$this, 'block_editor_assets']);
        add_action('wp_footer', [$this, 'inline_critical_css'], 1);
    }

    public function enqueue_styles(): void
    {
        wp_enqueue_style(
            'babarida-google-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap',
            array(),
            null
        );

        wp_enqueue_style(
            'babarida-style',
            BABARIDA_URI . '/assets/css/style.css',
            array(),
            BABARIDA_VERSION
        );
    }

    public function enqueue_scripts(): void
    {
        wp_enqueue_script(
            'babarida-app',
            BABARIDA_URI . '/assets/js/app.js',
            array(),
            BABARIDA_VERSION,
            array('strategy' => 'defer', 'in_footer' => true)
        );

        $localized_data = array(
            'ajaxUrl'   => admin_url('admin-ajax.php'),
            'nonce'     => wp_create_nonce('babarida_nonce'),
            'siteUrl'   => home_url('/'),
            'themeUrl'  => BABARIDA_URI,
            'i18n'      => array(
                'loading'    => __('Loading...', 'babarida-dive'),
                'error'      => __('Something went wrong. Please try again.', 'babarida-dive'),
                'subscribed' => __('Successfully subscribed!', 'babarida-dive'),
                'scrollTop'  => __('Back to Top', 'babarida-dive'),
            ),
            'whatsapp'  => array(
                'number' => '62895801960359',
                'link'   => 'https://wa.me/62895801960359',
            ),
            'clocks'    => array(
                'manado'    => 'Asia/Makassar',
                'jakarta'   => 'Asia/Jakarta',
                'singapore' => 'Asia/Singapore',
                'dubai'     => 'Asia/Dubai',
                'london'    => 'Europe/London',
                'newyork'   => 'America/New_York',
                'tokyo'     => 'Asia/Tokyo',
                'seoul'     => 'Asia/Seoul',
            ),
        );

        wp_localize_script('babarida-app', 'babaridaData', $localized_data);
    }

    public function block_editor_assets(): void
    {
        wp_enqueue_style(
            'babarida-editor',
            BABARIDA_URI . '/assets/css/editor.css',
            array(),
            BABARIDA_VERSION
        );
    }

    public function inline_critical_css(): void
    {
        if (is_admin()) {
            return;
        }
        ?>
        <style id="babarida-critical-css">
            *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
            html{scroll-behavior:smooth;-webkit-text-size-adjust:100%}
            body{font-family:'Inter',system-ui,-apple-system,sans-serif;font-size:16px;line-height:1.6;color:#0F172A;background:#fff;overflow-x:hidden}
            img{max-width:100%;height:auto;display:block}
            a{color:inherit;text-decoration:none}
            button{cursor:pointer;border:none;background:none;font-family:inherit}
            .container{width:100%;max-width:1280px;margin:0 auto;padding:0 20px}
            @media(min-width:768px){.container{padding:0 32px}}
            @media(min-width:1024px){.container{padding:0 40px}}
            #babarida-preloader{position:fixed;inset:0;z-index:99999;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0F172A 0%,#0c2340 50%,#0F172A 100%);transition:opacity .6s ease,visibility .6s ease}
            #babarida-preloader.loaded{opacity:0;visibility:hidden;pointer-events:none}
            .preloader-inner{position:relative;display:flex;flex-direction:column;align-items:center;gap:24px}
            .preloader-logo{opacity:0;animation:preloaderFadeIn .8s ease .3s forwards}
            .preloader-bar{width:200px;height:2px;background:rgba(255,255,255,.1);border-radius:2px;overflow:hidden}
            .preloader-bar-fill{width:0;height:100%;background:linear-gradient(90deg,#00BFFF,#FACC15);border-radius:2px;animation:preloaderBar 2s ease-in-out forwards}
            @keyframes preloaderFadeIn{to{opacity:1}}
            @keyframes preloaderBar{0%{width:0}50%{width:70%}100%{width:100%}}
            .hero-section{position:relative;width:100%;min-height:100vh;display:flex;align-items:center;justify-content:center;overflow:hidden}
            .hero-image-wrapper,.hero-video-wrapper{position:absolute;inset:0}
            .hero-image{width:100%;height:100%;object-fit:cover}
            .hero-content{position:relative;z-index:2;text-align:center;padding:0 20px;max-width:900px}
            .hero-title{font-family:'Playfair Display',serif;font-size:clamp(2.5rem,7vw,5.5rem);font-weight:700;color:#fff;line-height:1.1;margin-bottom:20px}
            .hero-title-accent{background:linear-gradient(135deg,#00BFFF,#FACC15);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
            .hero-subtitle{font-size:clamp(1rem,2.5vw,1.35rem);color:rgba(255,255,255,.9);font-weight:300;max-width:650px;margin:0 auto 36px;line-height:1.6}
            .btn{display:inline-flex;align-items:center;gap:10px;padding:14px 32px;border-radius:50px;font-weight:600;font-size:.95rem;transition:all .35s cubic-bezier(.4,0,.2,1);position:relative;overflow:hidden}
            .btn-primary{background:linear-gradient(135deg,#00BFFF,#1E90FF);color:#fff;box-shadow:0 4px 20px rgba(0,191,255,.35)}
            .btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(0,191,255,.5)}
            .btn-outline-white{border:2px solid rgba(255,255,255,.4);color:#fff}
            .btn-outline-white:hover{background:rgba(255,255,255,.1);border-color:#fff}
            .section-padding{padding:100px 0}
            @media(min-width:768px){.section-padding{padding:120px 0}}
            .section-dark{background:linear-gradient(180deg,#0F172A 0%,#0c2340 100%);color:#fff}
            .section-header{text-align:center;max-width:700px;margin:0 auto 60px}
            .section-label{display:inline-block;font-size:.8rem;font-weight:600;text-transform:uppercase;letter-spacing:3px;color:#00BFFF;margin-bottom:12px}
            .section-dark .section-label{color:#FACC15}
            .section-title{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,3rem);font-weight:700;line-height:1.2;margin-bottom:16px}
            .section-subtitle{font-size:1.05rem;opacity:.8;font-weight:300}
            .floating-contacts{position:fixed;bottom:24px;right:24px;z-index:9990;display:flex;flex-direction:column;gap:12px}
            .floating-btn{width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;box-shadow:0 4px 20px rgba(0,0,0,.2);transition:all .3s ease;position:relative}
            .floating-whatsapp{background:#25D366}
            .floating-whatsapp:hover{transform:scale(1.1);box-shadow:0 6px 28px rgba(37,211,102,.45)}
            .floating-email{background:#00BFFF}
            .floating-email:hover{transform:scale(1.1);box-shadow:0 6px 28px rgba(0,191,255,.45)}
            .floating-booking{background:linear-gradient(135deg,#00BFFF,#1E90FF)}
            .floating-booking:hover{transform:scale(1.1);box-shadow:0 6px 28px rgba(0,191,255,.45)}
            .floating-back-top{background:rgba(15,23,42,.8);backdrop-filter:blur(10px);transition:all .3s ease}
            .floating-back-top:hover{transform:scale(1.1);background:#00BFFF}
            .floating-btn-pulse{position:absolute;inset:-4px;border-radius:50%;background:rgba(37,211,102,.4);animation:floatPulse 2s ease-in-out infinite}
            @keyframes floatPulse{0%,100%{transform:scale(1);opacity:.6}50%{transform:scale(1.3);opacity:0}}
            .bubble{position:absolute;border-radius:50%;background:radial-gradient(circle at 30% 30%,rgba(255,255,255,.15),rgba(255,255,255,.03));border:1px solid rgba(255,255,255,.08);animation:bubbleFloat var(--bubble-duration,10s) ease-in-out infinite var(--bubble-delay,0s);width:var(--bubble-size,20px);height:var(--bubble-size,20px);left:var(--bubble-left,50%);bottom:-20px}
            @keyframes bubbleFloat{0%{transform:translateY(0) translateX(0) scale(1);opacity:.6}50%{transform:translateY(-50vh) translateX(20px) scale(1.05);opacity:.4}100%{transform:translateY(-110vh) translateX(-10px) scale(.9);opacity:0}}
            [data-animate]{opacity:0;transform:translateY(30px);transition:opacity .7s ease,transform .7s ease}
            [data-animate].is-visible{opacity:1;transform:translateY(0)}
            .screen-reader-text{border:0;clip:rect(1px,1px,1px,1px);clip-path:inset(50%);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute!important;width:1px;word-wrap:normal!important}
        </style>
        <?php
    }
}
