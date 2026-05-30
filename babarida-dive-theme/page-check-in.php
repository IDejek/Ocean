<?php
/**
 * Template Name: Online Check-In
 * 
 * @package Babarida_Dive_Theme
 * @version 7.0.0
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();

 $booking_code = isset($_GET['code']) ? sanitize_text_field(wp_unslash($_GET['code'])) : '';
 $status       = isset($_GET['status']) ? sanitize_text_field(wp_unslash($_GET['status'])) : '';
?>

<main id="primary" class="site-main checkin-main" role="main" style="margin-top: calc(var(--topbar-height) + var(--header-height) + 40px); min-height: 80vh;">
    <div class="container">
        <div class="checkin-wrapper" data-animate="fade-up">
            
            <header class="checkin-header">
                <h1 class="checkin-title"><?php esc_html_e('Online Check-In', 'babarida-dive'); ?></h1>
                <p class="checkin-subtitle"><?php esc_html_e('Enter your booking code to manage your trip, upload documents, and sign the digital waiver.', 'babarida-dive'); ?></p>
            </header>

            <!-- Step 1: Lookup -->
            <div class="checkin-step" id="checkin-lookup-step">
                <div class="checkin-card">
                    <form id="checkin-lookup-form" class="checkin-form">
                        <?php wp_nonce_field('babarida_checkin_lookup', 'checkin_lookup_nonce'); ?>
                        <div class="form-group">
                            <label for="booking-code-input"><?php esc_html_e('Booking Code', 'babarida-dive'); ?></label>
                            <div class="input-group">
                                <input type="text" id="booking-code-input" name="booking_code" value="<?php echo esc_attr($booking_code); ?>" placeholder="<?php esc_attr_e('e.g., BDC-2024-XXXXX', 'babarida-dive'); ?>" required autocomplete="off" class="form-control" style="text-transform: uppercase; letter-spacing: 2px; font-weight: 600; font-size: 1.2rem;">
                                <button type="submit" class="btn btn-primary"><?php esc_html_e('Find Booking', 'babarida-dive'); ?></button>
                            </div>
                        </div>
                    </form>
                    <div id="checkin-lookup-message" role="alert" aria-live="polite"></div>
                </div>
            </div>

            <!-- Step 2: Details & Actions (Hidden by default) -->
            <div class="checkin-step" id="checkin-details-step" style="display:none;">
                
                <?php if ($status === 'finish') : ?>
                    <div class="checkin-alert success">
                        <?php esc_html_e('Payment successful! Your booking is confirmed.', 'babarida-dive'); ?>
                    </div>
                <?php elseif ($status === 'pending') : ?>
                    <div class="checkin-alert warning">
                        <?php esc_html_e('Payment is pending. Please complete your payment to confirm the booking.', 'babarida-dive'); ?>
                    </div>
                <?php elseif ($status === 'error') : ?>
                    <div class="checkin-alert error">
                        <?php esc_html_e('Payment failed or was cancelled. Please try again or contact us.', 'babarida-dive'); ?>
                    </div>
                <?php endif; ?>

                <div class="checkin-grid">
                    <!-- Booking Info -->
                    <div class="checkin-card">
                        <h2 class="checkin-card-title"><?php esc_html_e('Booking Details', 'babarida-dive'); ?></h2>
                        <div id="booking-details-content">
                            <!-- Populated via AJAX -->
                            <div class="spinner-wrapper"><div class="spinner"></div></div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="checkin-actions-stack">
                        <!-- Passport Upload -->
                        <div class="checkin-card">
                            <h3 class="checkin-card-title"><?php esc_html_e('Passport Upload', 'babarida-dive'); ?></h3>
                            <p class="text-sm" style="color: var(--clr-gray-500); margin-bottom: 16px;"><?php esc_html_e('Please upload a clear photo of your passport data page.', 'babarida-dive'); ?></p>
                            <form id="passport-upload-form" class="checkin-form" enctype="multipart/form-data">
                                <?php wp_nonce_field('babarida_upload_passport', 'passport_nonce'); ?>
                                <input type="hidden" name="booking_id" id="passport-booking-id" value="">
                                <div class="file-upload-area" id="passport-dropzone">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    <span><?php esc_html_e('Drag & drop or click to select', 'babarida-dive'); ?></span>
                                    <input type="file" id="passport-file" name="passport_file" accept="image/*,.pdf" style="display:none;" required>
                                </div>
                                <button type="submit" class="btn btn-outline-primary btn-block" style="margin-top: 16px;" id="passport-submit-btn"><?php esc_html_e('Upload Passport', 'babarida-dive'); ?></button>
                            </form>
                            <div id="passport-message" role="alert"></div>
                        </div>

                        <!-- Digital Waiver -->
                        <div class="checkin-card">
                            <h3 class="checkin-card-title"><?php esc_html_e('Digital Waiver', 'babarida-dive'); ?></h3>
                            <p class="text-sm" style="color: var(--clr-gray-500); margin-bottom: 16px;"><?php esc_html_e('Please read and sign the liability waiver before your trip.', 'babarida-dive'); ?></p>
                            <div class="waiver-text-box" style="max-height: 200px; overflow-y: auto; background: var(--clr-gray-100); padding: 16px; border-radius: 8px; font-size: 0.85rem; color: var(--clr-gray-700); margin-bottom: 16px; line-height: 1.6;">
                                <?php esc_html_e('I, the undersigned, acknowledge that diving, snorkeling, and water sports involve inherent risks. I assume full responsibility for any personal injury, death, or property damage that may occur during activities with Babarida Dive Center. I certify that I am in good physical condition to participate and will follow all safety instructions provided by guides and staff. I release Babarida Dive Center, its owners, employees, and affiliates from any liability.', 'babarida-dive'); ?>
                            </div>
                            <form id="waiver-sign-form" class="checkin-form">
                                <?php wp_nonce_field('babarida_sign_waiver', 'waiver_nonce'); ?>
                                <input type="hidden" name="booking_id" id="waiver-booking-id" value="">
                                <div class="signature-pad-container" style="border: 2px dashed var(--clr-gray-200); border-radius: 8px; height: 150px; margin-bottom: 16px; background: #fff; position: relative; cursor: crosshair;">
                                    <canvas id="signature-canvas" style="width: 100%; height: 100%;"></canvas>
                                    <button type="button" class="clear-signature-btn" id="clear-signature" style="position:absolute; top:8px; right:8px; font-size:12px; color:var(--clr-gray-400);"><?php esc_html_e('Clear', 'babarida-dive'); ?></button>
                                </div>
                                <input type="hidden" name="signature_data" id="signature-data" value="">
                                <button type="submit" class="btn btn-primary btn-block" id="waiver-submit-btn"><?php esc_html_e('Accept & Sign Waiver', 'babarida-dive'); ?></button>
                            </form>
                            <div id="waiver-message" role="alert"></div>
                        </div>

                        <!-- Hotel Pickup -->
                        <div class="checkin-card">
                            <h3 class="checkin-card-title"><?php esc_html_e('Hotel Pickup Details', 'babarida-dive'); ?></h3>
                            <form id="pickup-form" class="checkin-form">
                                <?php wp_nonce_field('babarida_save_pickup', 'pickup_nonce'); ?>
                                <input type="hidden" name="booking_id" id="pickup-booking-id" value="">
                                <div class="form-group">
                                    <label><?php esc_html_e('Hotel / Villa Name', 'babarida-dive'); ?></label>
                                    <input type="text" name="pickup_hotel" id="pickup-hotel" class="form-control" placeholder="<?php esc_attr_e('e.g., Siladen Resort & Spa', 'babarida-dive'); ?>">
                                </div>
                                <div class="form-group">
                                    <label><?php esc_html_e('Room Number (Optional)', 'babarida-dive'); ?></label>
                                    <input type="text" name="pickup_room" id="pickup-room" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-outline-primary btn-block"><?php esc_html_e('Save Pickup Info', 'babarida-dive'); ?></button>
                            </form>
                            <div id="pickup-message" role="alert"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Signature Pad Library (Lightweight inline) -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const ajaxUrl = babaridaData.ajaxUrl;
    const nonce = babaridaData.nonce;

    // ─── LOOKUP BOOKING ─────────────────
    const lookupForm = document.getElementById('checkin-lookup-form');
    const lookupMsg = document.getElementById('checkin-lookup-message');
    const detailsStep = document.getElementById('checkin-details-step');
    
    if (lookupForm) {
        lookupForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const code = document.getElementById('booking-code-input').value.trim();
            if (!code) return;

            lookupMsg.innerHTML = '<div class="spinner" style="margin:20px auto;"></div>';
            
            const formData = new FormData();
            formData.append('action', 'babarida_checkin_lookup');
            formData.append('nonce', nonce);
            formData.append('booking_code', code);

            fetch(ajaxUrl, { method: 'POST', body: formData })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        detailsStep.style.display = 'block';
                        document.getElementById('booking-details-content').innerHTML = res.data.html;
                        document.getElementById('passport-booking-id').value = res.data.booking_id;
                        document.getElementById('waiver-booking-id').value = res.data.booking_id;
                        document.getElementById('pickup-booking-id').value = res.data.booking_id;
                        document.getElementById('pickup-hotel').value = res.data.pickup_hotel || '';
                        
                        initSignaturePad();
                        detailsStep.scrollIntoView({ behavior: 'smooth' });
                    } else {
                        lookupMsg.innerHTML = `<div class="checkin-alert error">${res.data.message}</div>`;
                    }
                })
                .catch(() => {
                    lookupMsg.innerHTML = `<div class="checkin-alert error"><?php esc_html_e('Network error.', 'babarida-dive'); ?></div>`;
                });
        });
    }

    // Auto-submit if code in URL
    if (document.getElementById('booking-code-input').value) {
        lookupForm.dispatchEvent(new Event('submit'));
    }

    // ─── PASSPORT UPLOAD ─────────────────
    const passportForm = document.getElementById('passport-upload-form');
    const dropzone = document.getElementById('passport-dropzone');
    const fileInput = document.getElementById('passport-file');

    if (dropzone && fileInput) {
        dropzone.addEventListener('click', () => fileInput.click());
        dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.style.borderColor = '#00BFFF'; });
        dropzone.addEventListener('dragleave', () => { dropzone.style.borderColor = ''; });
        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.style.borderColor = '';
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                dropzone.querySelector('span').textContent = e.dataTransfer.files[0].name;
            }
        });
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) dropzone.querySelector('span').textContent = fileInput.files[0].name;
        });
    }

    if (passportForm) {
        passportForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const msgEl = document.getElementById('passport-message');
            const btn = document.getElementById('passport-submit-btn');
            if (!fileInput.files.length) { msgEl.innerHTML = '<div class="checkin-alert error">Please select a file.</div>'; return; }
            
            btn.disabled = true; btn.textContent = 'Uploading...';
            const formData = new FormData(passportForm);
            formData.append('action', 'babarida_upload_passport');

            fetch(ajaxUrl, { method: 'POST', body: formData })
                .then(r => r.json())
                .then(res => {
                    msgEl.innerHTML = res.success ? `<div class="checkin-alert success">${res.data.message}</div>` : `<div class="checkin-alert error">${res.data.message}</div>`;
                })
                .finally(() => { btn.disabled = false; btn.textContent = 'Upload Passport'; });
        });
    }

    // ─── SIGNATURE PAD ────────────────────
    let signaturePad = null;
    function initSignaturePad() {
        const canvas = document.getElementById('signature-canvas');
        if (!canvas || typeof SignaturePad === 'undefined') return;
        signaturePad = new SignaturePad(canvas, { backgroundColor: 'rgb(255, 255, 255)', penColor: 'rgb(15, 23, 42)' });
        
        document.getElementById('clear-signature').addEventListener('click', () => signaturePad.clear());
    }

    const waiverForm = document.getElementById('waiver-sign-form');
    if (waiverForm) {
        waiverForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const msgEl = document.getElementById('waiver-message');
            if (!signaturePad || signaturePad.isEmpty()) { msgEl.innerHTML = '<div class="checkin-alert error">Please provide your signature.</div>'; return; }
            
            document.getElementById('signature-data').value = signaturePad.toDataURL('image/png');
            const formData = new FormData(waiverForm);
            formData.append('action', 'babarida_sign_waiver');

            fetch(ajaxUrl, { method: 'POST', body: formData })
                .then(r => r.json())
                .then(res => {
                    msgEl.innerHTML = res.success ? `<div class="checkin-alert success">${res.data.message}</div>` : `<div class="checkin-alert error">${res.data.message}</div>`;
                    if (res.success) waiverForm.style.display = 'none';
                });
        });
    }

    // ─── PICKUP FORM ─────────────────────
    const pickupForm = document.getElementById('pickup-form');
    if (pickupForm) {
        pickupForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const msgEl = document.getElementById('pickup-message');
            const formData = new FormData(pickupForm);
            formData.append('action', 'babarida_save_pickup');

            fetch(ajaxUrl, { method: 'POST', body: formData })
                .then(r => r.json())
                .then(res => {
                    msgEl.innerHTML = res.success ? `<div class="checkin-alert success">${res.data.message}</div>` : `<div class="checkin-alert error">${res.data.message}</div>`;
                });
        });
    }
});
</script>

<style>
    .checkin-wrapper { max-width: 900px; margin: 0 auto; padding-bottom: 80px; }
    .checkin-header { text-align: center; margin-bottom: 48px; }
    .checkin-title { font-family: var(--ff-display); font-size: clamp(2rem, 4vw, 3rem); color: var(--clr-dark); margin-bottom: 12px; }
    .checkin-subtitle { color: var(--clr-gray-500); font-size: var(--fs-lg); max-width: 600px; margin: 0 auto; }
    .checkin-card { background: var(--clr-white); border: 1px solid var(--clr-gray-200); border-radius: var(--radius-lg); padding: 32px; margin-bottom: 24px; box-shadow: var(--shadow-sm); }
    .checkin-card-title { font-family: var(--ff-display); font-size: var(--fs-xl); color: var(--clr-dark); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid var(--clr-primary); }
    .checkin-grid { display: grid; grid-template-columns: 1fr; gap: 24px; }
    @media(min-width: 768px) { .checkin-grid { grid-template-columns: 1fr 1fr; } }
    .checkin-actions-stack { display: flex; flex-direction: column; gap: 24px; }
    .input-group { display: flex; gap: 12px; }
    @media(max-width: 480px) { .input-group { flex-direction: column; } }
    .input-group .form-control { flex: 1; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-weight: 600; font-size: var(--fs-sm); color: var(--clr-dark); margin-bottom: 6px; }
    .form-control { width: 100%; padding: 12px 16px; border: 1px solid var(--clr-gray-200); border-radius: var(--radius-md); font-size: var(--fs-base); transition: border-color 0.2s; }
    .form-control:focus { outline: none; border-color: var(--clr-primary); box-shadow: 0 0 0 3px rgba(0,191,255,0.15); }
    .file-upload-area { border: 2px dashed var(--clr-gray-300); border-radius: var(--radius-md); padding: 32px; text-align: center; color: var(--clr-gray-400); cursor: pointer; transition: all 0.2s; display: flex; flex-direction: column; align-items: center; gap: 12px; }
    .file-upload-area:hover { border-color: var(--clr-primary); background: rgba(0,191,255,0.03); }
    .checkin-alert { padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 24px; font-weight: 500; }
    .checkin-alert.success { background: #f0fdf4; color: #166534; border-left: 4px solid #22c55e; }
    .checkin-alert.warning { background: #fffbeb; color: #92400e; border-left: 4px solid #f59e0b; }
    .checkin-alert.error { background: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444; }
    .text-sm { font-size: var(--fs-sm); }
</style>

<?php get_footer(); ?>
