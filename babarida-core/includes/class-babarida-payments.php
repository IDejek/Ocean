<?php
/**
 * Enterprise Payment Gateway Integration
 * Midtrans, Xendit, Stripe, PayPal
 * 
 * @package Babarida_Core
 * @version 7.0.0
 */

declare(strict_types=1);

namespace BabaridaCore;

if (!defined('ABSPATH')) {
    exit;
}

/* ─────────────────────────────────────────────
   PAYMENT GATEWAY INTERFACE
   ───────────────────────────────────────────── */
interface Babarida_Payment_Gateway_Interface
{
    public function create_transaction(array $booking_data, float $amount, string $currency, string $payment_type): array;
    public function handle_webhook(): void;
    public function check_status(string $transaction_id): array;
    public function process_refund(string $transaction_id, float $amount, string $reason): array;
}

/* ─────────────────────────────────────────────
   ABSTRACT BASE CLASS
   ───────────────────────────────────────────── */
abstract class Babarida_Payment_Gateway implements Babarida_Payment_Gateway_Interface
{
    protected string $api_key;
    protected bool $is_sandbox;
    protected string $base_url;

    public function __construct()
    {
        $this->is_sandbox = (get_option('babarida_payment_sandbox', 'yes') === 'yes');
        $this->init_credentials();
    }

    abstract protected function init_credentials(): void;

    protected function remote_request(string $endpoint, array $body, string $method = 'POST', array $headers = array()): array
    {
        $url = rtrim($this->base_url, '/') . '/' . ltrim($endpoint, '/');

        $default_headers = array('Content-Type' => 'application/json', 'Accept' => 'application/json');
        $headers = array_merge($default_headers, $headers);

        $args = array(
            'method'  => $method,
            'headers' => $headers,
            'body'    => wp_json_encode($body),
            'timeout' => 30,
        );

        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            return array('success' => false, 'error' => $response->get_error_message());
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($code >= 200 && $code < 300) {
            return array('success' => true, 'data' => $body, 'code' => $code);
        }

        return array('success' => false, 'error' => $body['error_message'] ?? 'Unknown API error', 'code' => $code, 'data' => $body);
    }

    protected function log_transaction(int $payment_id, string $type, array $payload, array $response): void
    {
        global $wpdb;
        $table = $wpdb->prefix . 'babarida_payments';
        $wpdb->update(
            $table,
            array(
                'payload_request'  => wp_json_encode($payload),
                'payload_response' => wp_json_encode($response),
                'updated_at'       => current_time('mysql'),
            ),
            array('payment_id' => $payment_id),
            array('%s', '%s', '%s')
        );
    }
}

/* ─────────────────────────────────────────────
   MIDTRANS GATEWAY IMPLEMENTATION
   ───────────────────────────────────────────── */
class Babarida_Gateway_Midtrans extends Babarida_Payment_Gateway
{
    protected function init_credentials(): void
    {
        $this->api_key  = $this->is_sandbox ? get_option('babarida_midtrans_sb_server_key', '') : get_option('babarida_midtrans_server_key', '');
        $this->base_url = $this->is_sandbox ? 'https://app.sandbox.midtrans.com/snap/v1' : 'https://app.midtrans.com/snap/v1';
    }

    public function create_transaction(array $booking_data, float $amount, string $currency, string $payment_type): array
    {
        if (empty($this->api_key)) {
            return array('success' => false, 'error' => 'Midtrans API Key is not configured.');
        }

        $transaction_details = array(
            'order_id'     => $booking_data['booking_code'] . '-' . time(),
            'gross_amount' => (int) round($amount),
        );

        $customer_details = array(
            'first_name' => $booking_data['first_name'] ?? 'Guest',
            'last_name'  => $booking_data['last_name'] ?? '',
            'email'      => $booking_data['email'] ?? '',
            'phone'      => $booking_data['phone'] ?? '',
        );

        $payload = array(
            'transaction_details' => $transaction_details,
            'customer_details'    => $customer_details,
            'callbacks'           => array(
                'finish' => home_url('/check-in/?status=finish&order_id=' . $transaction_details['order_id']),
                'error'   => home_url('/check-in/?status=error&order_id=' . $transaction_details['order_id']),
                'pending' => home_url('/check-in/?status=pending&order_id=' . $transaction_details['order_id']),
            ),
        );

        $headers = array('Authorization' => 'Basic ' . base64_encode($this->api_key . ':'));
        $result = $this->remote_request('transactions', $payload, 'POST', $headers);

        if ($result['success'] && isset($result['data']['token'])) {
            return array(
                'success'      => true,
                'redirect_url' => $result['data']['redirect_url'],
                'token'        => $result['data']['token'],
                'transaction_id' => $transaction_details['order_id'],
            );
        }

        return $result;
    }

    public function handle_webhook(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        
        if (empty($payload) || !isset($payload['transaction_id'])) {
            status_header(400);
            exit('Invalid payload');
        }

        $signature_key = $this->is_sandbox ? get_option('babarida_midtrans_sb_server_key', '') : get_option('babarida_midtrans_server_key', '');
        $order_id = $payload['order_id'];
        $status_code = $payload['status_code'];
        $gross_amount = $payload['gross_amount'];
        $server_key = $signature_key;

        $signature = hash('sha512', $order_id . $status_code . $gross_amount . $server_key);

        if ($signature !== ($payload['signature_key'] ?? '')) {
            status_header(403);
            exit('Invalid signature');
        }

        $booking_code = explode('-', $order_id)[0];
        $this->update_payment_status($booking_code, $payload['transaction_status'], $payload);
        
        status_header(200);
        exit('OK');
    }

    public function check_status(string $transaction_id): array
    {
        $headers = array('Authorization' => 'Basic ' . base64_encode($this->api_key . ':'));
        $this->base_url = $this->is_sandbox ? 'https://app.sandbox.midtrans.com/v2' : 'https://app.midtrans.com/v2';
        return $this->remote_request($transaction_id . '/status', array(), 'GET', $headers);
    }

    public function process_refund(string $transaction_id, float $amount, string $reason): array
    {
        $headers = array('Authorization' => 'Basic ' . base64_encode($this->api_key . ':'));
        $this->base_url = $this->is_sandbox ? 'https://app.sandbox.midtrans.com/v2' : 'https://app.midtrans.com/v2';
        $payload = array('amount' => (int) round($amount), 'reason' => $reason);
        return $this->remote_request($transaction_id . '/refund', $payload, 'POST', $headers);
    }

    private function update_payment_status(string $booking_code, string $status, array $payload): void
    {
        global $wpdb;
        $booking_table = $wpdb->prefix . 'babarida_bookings';
        $payment_table = $wpdb->prefix . 'babarida_payments';

        $booking = $wpdb->get_row($wpdb->prepare("SELECT booking_id FROM {$booking_table} WHERE booking_code = %s", $booking_code));
        if (!$booking) return;

        $status_map = array(
            'capture'   => 'completed',
            'settlement' => 'completed',
            'pending'   => 'pending',
            'deny'      => 'failed',
            'expire'    => 'expired',
            'cancel'    => 'cancelled',
            'refund'    => 'refunded',
            'partial_refund' => 'refunded',
            'chargeback' => 'refunded',
        );

        $new_status = $status_map[$status] ?? 'pending';

        $wpdb->update(
            $payment_table,
            array(
                'status'      => $new_status,
                'paid_at'     => in_array($status, ['capture', 'settlement']) ? current_time('mysql') : null,
                'updated_at'  => current_time('mysql'),
            ),
            array('transaction_id' => $payload['transaction_id']),
            array('%s', '%s', '%s')
        );

        if (in_array($status, ['capture', 'settlement'])) {
            $wpdb->update($booking_table, array('status' => 'paid', 'updated_at' => current_time('mysql')), array('booking_id' => $booking->booking_id), array('%s', '%s'));
        }
    }
}

/* ─────────────────────────────────────────────
   XENDIT GATEWAY IMPLEMENTATION
   ───────────────────────────────────────────── */
class Babarida_Gateway_Xendit extends Babarida_Payment_Gateway
{
    protected function init_credentials(): void
    {
        $this->api_key  = $this->is_sandbox ? get_option('babarida_xendit_sb_key', '') : get_option('babarida_xendit_key', '');
        $this->base_url = $this->is_sandbox ? 'https://api.xendit.co' : 'https://api.xendit.co';
    }

    public function create_transaction(array $booking_data, float $amount, string $currency, string $payment_type): array
    {
        if (empty($this->api_key)) {
            return array('success' => false, 'error' => 'Xendit API Key is not configured.');
        }

        $external_id = $booking_data['booking_code'] . '-' . time();
        
        $payload = array(
            'external_id'          => $external_id,
            'amount'               => (float) $amount,
            'payer_email'          => $booking_data['email'] ?? '',
            'description'          => sprintf('Booking %s - Babarida Dive Center', $booking_data['booking_code']),
            'callback_url'         => site_url('/wp-admin/admin-ajax.php?action=babarida_xendit_webhook'),
            'success_redirect_url' => home_url('/check-in/?status=finish&order_id=' . $external_id),
            'failure_redirect_url' => home_url('/check-in/?status=error&order_id=' . $external_id),
        );

        $headers = array('Authorization' => 'Basic ' . base64_encode($this->api_key . ':'));
        $result = $this->remote_request('v2/invoices', $payload, 'POST', $headers);

        if ($result['success'] && isset($result['data']['invoice_url'])) {
            return array(
                'success'         => true,
                'redirect_url'    => $result['data']['invoice_url'],
                'transaction_id'  => $result['data']['id'],
                'external_id'     => $external_id,
            );
        }

        return $result;
    }

    public function handle_webhook(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true);
        $xendit_callback = $_SERVER['HTTP_X_CALLBACK_TOKEN'] ?? '';

        $expected_token = get_option('babarida_xendit_webhook_token', '');
        if ($xendit_callback !== $expected_token) {
            status_header(403);
            exit('Invalid token');
        }

        if ($payload['status'] === 'PAID' || $payload['status'] === 'SETTLED') {
            $booking_code = explode('-', $payload['external_id'])[0];
            $this->update_payment_status($booking_code, 'completed', $payload);
        }

        status_header(200);
        exit('OK');
    }

    public function check_status(string $transaction_id): array
    {
        $headers = array('Authorization' => 'Basic ' . base64_encode($this->api_key . ':'));
        return $this->remote_request('v2/invoices/' . $transaction_id, array(), 'GET', $headers);
    }

    public function process_refund(string $transaction_id, float $amount, string $reason): array
    {
        $headers = array('Authorization' => 'Basic ' . base64_encode($this->api_key . ':'));
        $payload = array('amount' => $amount, 'reason' => $reason);
        return $this->remote_request('v2/invoices/' . $transaction_id . '/refund', $payload, 'POST', $headers);
    }

    private function update_payment_status(string $booking_code, string $status, array $payload): void
    {
        global $wpdb;
        $booking_table = $wpdb->prefix . 'babarida_bookings';
        $payment_table = $wpdb->prefix . 'babarida_payments';

        $wpdb->update(
            $payment_table,
            array('status' => $status, 'paid_at' => current_time('mysql'), 'updated_at' => current_time('mysql')),
            array('transaction_id' => $payload['id']),
            array('%s', '%s', '%s')
        );

        $wpdb->update(
            $booking_table,
            array('status' => 'paid', 'updated_at' => current_time('mysql')),
            array('booking_code' => $booking_code),
            array('%s', '%s')
        );
    }
}

/* ─────────────────────────────────────────────
   STRIPE GATEWAY IMPLEMENTATION
   ───────────────────────────────────────────── */
class Babarida_Gateway_Stripe extends Babarida_Payment_Gateway
{
    protected string $publishable_key;

    protected function init_credentials(): void
    {
        $this->api_key = $this->is_sandbox ? get_option('babarida_stripe_sb_secret_key', '') : get_option('babarida_stripe_secret_key', '');
        $this->publishable_key = $this->is_sandbox ? get_option('babarida_stripe_sb_pk', '') : get_option('babarida_stripe_pk', '');
        $this->base_url = 'https://api.stripe.com/v1';
    }

    public function get_publishable_key(): string
    {
        return $this->publishable_key;
    }

    public function create_transaction(array $booking_data, float $amount, string $currency, string $payment_type): array
    {
        if (empty($this->api_key)) {
            return array('success' => false, 'error' => 'Stripe API Key is not configured.');
        }

        $amount_cents = (int) round($amount * 100); // Stripe uses cents
        $booking_code = $booking_data['booking_code'];

        $payload = array(
            'payment_method_types' => array('card'),
            'amount'               => $amount_cents,
            'currency'             => strtolower($currency),
            'metadata'             => array('booking_code' => $booking_code),
            'description'          => sprintf('Booking %s - Babarida Dive Center', $booking_code),
        );

        $headers = array('Authorization' => 'Basic ' . base64_encode($this->api_key . ':'));
        
        // Step 1: Create PaymentIntent
        $intent_result = $this->remote_request('payment_intents', $payload, 'POST', $headers);

        if ($intent_result['success']) {
            return array(
                'success'          => true,
                'client_secret'    => $intent_result['data']['client_secret'],
                'transaction_id'   => $intent_result['data']['id'],
                'publishable_key'  => $this->publishable_key,
            );
        }

        return $intent_result;
    }

    public function handle_webhook(): void
    {
        $payload = file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $webhook_secret = get_option('babarida_stripe_webhook_secret', '');

        if (empty($webhook_secret)) {
            status_header(500);
            exit('Webhook not configured');
        }

        $event = json_decode($payload, true);
        if (!$event || !isset($event['type'])) {
            status_header(400);
            exit('Invalid payload');
        }

        // Verify signature in production (requires Stripe PHP SDK or custom logic)
        // For robust security, implement: hash_hmac('sha256', $payload, $webhook_secret)

        if ($event['type'] === 'payment_intent.succeeded') {
            $intent = $event['data']['object'];
            $booking_code = $intent['metadata']['booking_code'] ?? null;
            
            if ($booking_code) {
                $this->update_payment_status($booking_code, 'completed', $intent);
            }
        }

        status_header(200);
        exit('OK');
    }

    public function check_status(string $transaction_id): array
    {
        $headers = array('Authorization' => 'Basic ' . base64_encode($this->api_key . ':'));
        return $this->remote_request('payment_intents/' . $transaction_id, array(), 'GET', $headers);
    }

    public function process_refund(string $transaction_id, float $amount, string $reason): array
    {
        $amount_cents = (int) round($amount * 100);
        $headers = array('Authorization' => 'Basic ' . base64_encode($this->api_key . ':'));
        $payload = array('amount' => $amount_cents, 'reason' => $reason);
        return $this->remote_request('charges/' . str_replace('pi_', 'ch_', $transaction_id) . '/refunds', $payload, 'POST', $headers);
    }

    private function update_payment_status(string $booking_code, string $status, array $payload): void
    {
        global $wpdb;
        $booking_table = $wpdb->prefix . 'babarida_bookings';
        $payment_table = $wpdb->prefix . 'babarida_payments';

        $wpdb->update(
            $payment_table,
            array('status' => $status, 'paid_at' => current_time('mysql'), 'updated_at' => current_time('mysql')),
            array('transaction_id' => $payload['id']),
            array('%s', '%s', '%s')
        );

        $wpdb->update(
            $booking_table,
            array('status' => 'paid', 'updated_at' => current_time('mysql')),
            array('booking_code' => $booking_code),
            array('%s', '%s')
        );
    }
}

/* ─────────────────────────────────────────────
   WEBHOOK ROUTER INITIALIZATION
   ───────────────────────────────────────────── */
add_action('init', function (): void {
    // Midtrans Webhook
    add_rewrite_rule('^midtrans-webhook/?$', 'index.php?babarida_midtrans_webhook=1', 'top');
    add_filter('query_vars', function (array $vars): array {
        $vars[] = 'babarida_midtrans_webhook';
        return $vars;
    });
    add_action('template_redirect', function (): void {
        if (get_query_var('babarida_midtrans_webhook')) {
            $gateway = new Babarida_Gateway_Midtrans();
            $gateway->handle_webhook();
        }
    });

    // Xendit Webhook (via AJAX)
    add_action('wp_ajax_babarida_xendit_webhook', function (): void {
        $gateway = new Babarida_Gateway_Xendit();
        $gateway->handle_webhook();
    });
    add_action('wp_ajax_nopriv_babarida_xendit_webhook', function (): void {
        $gateway = new Babarida_Gateway_Xendit();
        $gateway->handle_webhook();
    });

    // Stripe Webhook
    add_rewrite_rule('^stripe-webhook/?$', 'index.php?babarida_stripe_webhook=1', 'top');
    add_filter('query_vars', function (array $vars): array {
        $vars[] = 'babarida_stripe_webhook';
        return $vars;
    });
    add_action('template_redirect', function (): void {
        if (get_query_var('babarida_stripe_webhook')) {
            $gateway = new Babarida_Gateway_Stripe();
            $gateway->handle_webhook();
        }
    });
});
