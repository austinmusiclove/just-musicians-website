<?php
/**
 * Stripe Checkout Session Completed Handler
 *
 * Handles the checkout.session.completed event from Stripe webhooks.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function handle_stripe_checkout_session_completed($session) {
    error_log('Handle Stripe event: checkout.session.completed');

    $email = $session->customer_email ?? $session->customer_details->email ?? '';
    $product = $session->metadata->product ?? '';
    if (empty($email) || empty($product)) {
        return new WP_Error('missing_data', 'Missing email or product in session', ['status' => 400]);
    }

    $valid_products = hm_stripe_valid_products();
    if (!in_array($product, array_keys($valid_products), true)) {
        return new WP_Error('invalid_product', 'Invalid product: ' . $product, ['status' => 400]);
    }
    $product_name = $valid_products[$product]['name'];
    $capabilities = $valid_products[$product]['capabilities'];

    // Track the user's existing (canonical) Stripe customer id before any update,
    // so we can detect duplicates and check the old customer for cancellations.
    $old_customer_id = '';
    $new_customer_id = $session->customer ?? '';
    $user = get_user_by('email', $email);

    if (!$user) {
        $user_login = $email;
        $display_name = $first_name = $last_name = '';
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
                'stripe_customer_id' => $new_customer_id,
                'email_verified'     => false,
                'account_identifier' => $account_identifier,
            ],
        ]);
        if (is_wp_error($user_id)) {
            return new WP_Error('user_creation_failed', 'Error creating user: ' . $user_id->get_error_message(), ['status' => 500]);
        }
        $user = get_userdata($user_id);

        // send an email to the admin
        wp_new_user_notification($user_id);
        // send password reset email
        retrieve_password($user->user_login);
        // send email verification email
        send_account_activation_email($email, $account_identifier);
    } else {
        if (!empty($new_customer_id)) {
            update_user_meta($user->ID, 'stripe_customer_id', $new_customer_id);
        }
        // if there is a failure to match up the customer id, notify admin
        $old_customer_id = get_user_meta($user->ID, 'stripe_customer_id', true);
        if (!empty($new_customer_id) && !empty($old_customer_id) && $new_customer_id != $old_customer_id) {
            notify_duplicate_stripe_customer($email, $old_customer_id, $new_customer_id, $session->id ?? '');
        }
    }

    // Add capabiliteis to user
    foreach ($capabilities as $cap) {
        $user->add_cap($cap);
    }

    // If the user is buying lifetime, cancel their active monthly subscription.
    if ($product === 'buyer-pro-lifetime' && defined('STRIPE_PRO_PRICE_ID')) {
        $customers_to_check = array_values(array_filter(array_unique([$new_customer_id, $old_customer_id])));
        foreach ($customers_to_check as $customer_id) {
            if (empty($customer_id)) { continue; }
            try {
                $subscriptions = \Stripe\Subscription::all([
                    'customer' => $customer_id,
                    'status'   => 'active',
                ]);

                foreach ($subscriptions->data as $subscription) {
                    foreach ($subscription->items->data as $item) {
                        if ($item->price->id === STRIPE_PRO_PRICE_ID) {
                            error_log('Cancelling monthly subscription ' . $subscription->id . ' for lifetime upgrade');
                            $subscription->cancel();
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                error_log('Failed to cancel monthly subscription on lifetime upgrade: ' . $e->getMessage());
            }
        }
    }

    send_subscription_confirmation_email($email, $product_name);

    return new WP_REST_Response(['status' => 'success', 'user_id' => $user->ID], 200);
}
