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
require_once __DIR__ . '/stripe-checkout/webhook.php';

add_action('rest_api_init', function () {
    register_rest_route('stripe-checkout/v1', 'webhook', [
        'methods'             => WP_REST_SERVER::CREATABLE,
        'callback'            => 'handle_stripe_webhook',
        'permission_callback' => '__return_true',
    ]);
});

function hm_stripe_valid_products() {
    return [
        'buyer-pro-monthly'  => [
            'name'         => 'Talent Buyer Pro',
            'capabilities' => ['hm_buyer_pro'],
            'price_id'     => defined('STRIPE_PRO_PRICE_ID') ? STRIPE_PRO_PRICE_ID : '',
        ],
        'buyer-pro-lifetime' => [
            'name'         => 'Talent Buyer Pro Lifetime Membership',
            'capabilities' => ['hm_buyer_pro', 'hm_buyer_pro_lifetime'],
            'price_id'     => defined('STRIPE_LIFETIME_PRICE_ID') ? STRIPE_LIFETIME_PRICE_ID : '',
        ],
    ];
}
