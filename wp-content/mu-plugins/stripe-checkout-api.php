<?php
/**
 * Plugin Name: Hire Musicians Stripe Checkout API
 * Description: Handles Stripe webhook for checkout.session.completed events
 * Version: 1.0
 * Author: John Filippone
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once get_template_directory() . '/lib/php/stripe-php/init.php';

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

    error_log($event->type);
    if ($event->type !== 'checkout.session.completed') {
        error_log('Ingoring stripe event: ' . $event->type);
        return new WP_REST_Response(['status' => 'ignored', 'type' => $event->type], 200);
    }

    $session = $event->data->object;
    $email   = $session->customer_email ?? $session->customer_details->email ?? '';
    $tier    = $session->metadata->tier ?? '';

    $display_name = '';
    $first_name   = '';
    $last_name    = '';
    if (!empty($session->custom_fields)) {
        foreach ($session->custom_fields as $field) {
            if ($field->key === 'first_name') { $first_name = trim($field->text->value ?? ''); }
            if ($field->key === 'last_name')  { $last_name  = trim($field->text->value ?? ''); }
        }
        $display_name = trim($first_name . ' ' . $last_name);
    }
    if (empty($display_name)) {
        $display_name = trim($session->customer_details->name ?? '');
    }



    if (empty($email) || empty($tier)) {
        return new WP_Error('missing_data', 'Missing email or tier in session', ['status' => 400]);
    }

    $valid_tiers = ['buyer-pro-monthly', 'buyer-pro-life'];
    if (!in_array($tier, $valid_tiers, true)) {
        return new WP_Error('invalid_tier', 'Invalid tier: ' . $tier, ['status' => 400]);
    }

    $cap_map = [
        'buyer-pro-monthly' => 'hm_pro_buyer',
        'buyer-pro-life'    => 'hm_pro_buyer',
    ];
    $capability = $cap_map[$tier];
    $user = get_user_by('email', $email);

    if (!$user) {
        $random_password = wp_generate_password(24, true);
        $user_login      = sanitize_title($email);

        $original_login = $user_login;
        $counter        = 1;
        while (username_exists($user_login)) {
            $user_login = $original_login . '-' . $counter;
            $counter++;
        }

        $user_id = wp_insert_user([
            'user_login'      => $user_login,
            'user_email'      => $email,
            'user_pass'       => $random_password,
            'first_name'      => $first_name,
            'last_name'       => $last_name,
            'display_name'    => $display_name,
            'role'            => 'subscriber',
            'user_registered' => date('Y-m-d H:i:s'),
            'meta_input'      => [
                'stripe_customer_id' => $session->customer ?? '',
                'stripe_session_id'  => $session->id ?? '',
                'membership_tier'    => $tier,
            ],
        ]);

        if (is_wp_error($user_id)) {
            return new WP_Error('user_creation_failed', 'Error creating user: ' . $user_id->get_error_message(), ['status' => 500]);
        }

        $user = get_userdata($user_id);
        $user->add_cap($capability);
    } else {
        $user->add_cap($capability);
        wp_update_user([
            'ID'           => $user->ID,
            'first_name'   => $first_name,
            'last_name'    => $last_name,
            'display_name' => $display_name,
        ]);
        update_user_meta($user->ID, 'stripe_customer_id', $session->customer ?? '');
        update_user_meta($user->ID, 'stripe_session_id', $session->id ?? '');
        update_user_meta($user->ID, 'membership_tier', $tier);
    }

    $reset_key = get_password_reset_key($user);

    if ($reset_key && !is_wp_error($reset_key)) {
        $reset_link = network_site_url("wp-login.php?action=rp&key={$reset_key}&login=" . rawurlencode($user->user_login), 'login');

        $subject = 'Welcome to Hire Musicians — Set Your Password';
        $message = "Hi there,\n\n";
        $message .= "Your Pro Talent Buyer account has been created!\n\n";
        $message .= "Click the link below to set your password and log in:\n\n";
        $message .= $reset_link . "\n\n";
        $message .= "If you didn't expect this email, you can safely ignore it.\n\n";
        $message .= "Thanks,\nThe Hire Musicians Team";

        wp_mail($user->user_email, $subject, $message);
    }

    return new WP_REST_Response(['status' => 'success', 'user_id' => $user->ID], 200);
}
