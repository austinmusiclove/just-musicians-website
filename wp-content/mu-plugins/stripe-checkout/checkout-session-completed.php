<?php
/**
 * Stripe Checkout Session Completed Handler
 *
 * Handles the checkout.session.completed event from Stripe webhooks.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function handle_stripe_checkout_session_completed($session) {
    $email = $session->customer_email ?? $session->customer_details->email ?? '';
    $tier  = $session->metadata->tier ?? '';

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
        $user_login = $email;

        $original_login = $user_login;
        $counter        = 1;
        while (username_exists($user_login)) {
            $user_login = $original_login . '-' . $counter;
            $counter++;
        }

        $account_identifier = md5(rand()); // secret used to verifiy email
        $user_id = wp_insert_user([
            'user_login'      => $user_login,
            'user_email'      => $email,
            'user_pass'       => wp_generate_password(24, true),
            'first_name'      => $first_name,
            'last_name'       => $last_name,
            'display_name'    => $display_name,
            'role'            => 'subscriber',
            'user_registered' => date('Y-m-d H:i:s'),
            'meta_input'      => [
                'stripe_customer_id' => $session->customer ?? '',
                'stripe_session_id'  => $session->id ?? '',
                'membership_tier'    => $tier,
                'email_verified'     => false,
                'account_identifier' => $account_identifier,
            ],
        ]);

        if (is_wp_error($user_id)) {
            return new WP_Error('user_creation_failed', 'Error creating user: ' . $user_id->get_error_message(), ['status' => 500]);
        }

        $user = get_userdata($user_id);
        $user->add_cap($capability);

        // send an email to the admin
        wp_new_user_notification($user_id);
        // send email verification email
        send_account_activation_email($email, $account_identifier);
        // send password reset email
        retrieve_password($user->user_login);
        // log the new user in
        wp_set_auth_cookie($user_id, false);
        wp_set_current_user($user_id, $user->user_login);
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

    return new WP_REST_Response(['status' => 'success', 'user_id' => $user->ID], 200);
}
