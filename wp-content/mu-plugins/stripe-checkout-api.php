<?php
/**
 * Plugin Name: Hire Musicians Stripe Checkout API
 * Description: Handles Stripe webhook events
 * Version: 1.0
 * Author: John Filippone
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once get_template_directory() . '/lib/php/stripe-php/init.php';
require_once __DIR__ . '/stripe-checkout/checkout-session-completed.php';

add_action('rest_api_init', function () {
    register_rest_route('stripe-checkout/v1', 'webhook', [
        'methods'             => WP_REST_SERVER::CREATABLE,
        'callback'            => 'handle_stripe_webhook',
        'permission_callback' => '__return_true',
    ]);
});

function handle_stripe_webhook(WP_REST_Request $request) {
    \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

    $payload    = $request->get_body();
    $sig_header = $request->get_header('stripe-signature');

    if (empty($sig_header)) {
        return new WP_Error('missing_signature', 'Missing Stripe signature header', ['status' => 400]);
    }

    try {
        $event = \Stripe\Webhook::constructEvent(
            $payload,
            $sig_header,
            defined('STRIPE_WEBHOOK_SECRET') ? STRIPE_WEBHOOK_SECRET : ''
        );
    } catch (\UnexpectedValueException $e) {
        return new WP_Error('invalid_payload', 'Invalid payload', ['status' => 400]);
    } catch (\Stripe\Exception\SignatureVerificationException $e) {
        return new WP_Error('invalid_signature', 'Invalid signature', ['status' => 400]);
    }

    $handlers = [
        'checkout.session.completed' => 'handle_stripe_checkout_session_completed',
    ];

    if (isset($handlers[$event->type])) {
        return $handlers[$event->type]($event->data->object);
    }

    error_log('Unhandled Stripe event: ' . $event->type);
    error_log('Unhanlded Stripe event payload: ' . wp_json_encode($event, JSON_PRETTY_PRINT));
    return new WP_REST_Response(['status' => 'ignored', 'type' => $event->type], 200);
}
