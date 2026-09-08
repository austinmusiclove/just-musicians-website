<?php
/**
 * Stripe Subscription Cancellation Handler
 *
 * Cancels the user's active Talent Buyer Pro monthly subscription and
 * removes the corresponding capability.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function handle_cancel_subscription() {

    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must sign in to cancel your subscription.');
    }

    $user = wp_get_current_user();

    if (!$user->has_cap('hm_buyer_pro')) {
        return new WP_Error('no_subscription', 'You do not have an active Talent Buyer Pro subscription.');
    }

    $customer_id = get_user_meta($user->ID, 'stripe_customer_id', true);
    if (empty($customer_id)) {
        return new WP_Error('no_customer', 'No Stripe customer found for your account.');
    }

    \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

    $cancelled = false;
    try {
        $subscriptions = \Stripe\Subscription::all([
            'customer' => $customer_id,
            'status'   => 'active',
        ]);

        foreach ($subscriptions->data as $subscription) {
            foreach ($subscription->items->data as $item) {
                if (defined('STRIPE_PRO_PRICE_ID') && $item->price->id === STRIPE_PRO_PRICE_ID) {
                    error_log('Cancelling monthly subscription ' . $subscription->id . ' for user ' . $user->ID);
                    $subscription->cancel();
                    $cancelled = true;
                    break;
                }
            }
        }
    } catch (\Exception $e) {
        error_log('Failed to cancel subscription: ' . $e->getMessage());
        return new WP_Error('cancel_failed', 'Failed to cancel your subscription. Please try again.', ['status' => 500]);
    }

    if (!$cancelled) {
        return new WP_Error('no_subscription_found', 'No active Talent Buyer Pro subscription was found to cancel.');
    }

    // Remove the Pro capability (lifetime cap is untouched)
    $user->remove_cap('hm_buyer_pro');

    return true;
}
