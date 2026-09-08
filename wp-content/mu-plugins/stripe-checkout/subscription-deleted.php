<?php
/**
 * Stripe Subscription Deleted Handler
 *
 * Handles the customer.subscription.deleted event from Stripe webhooks.
 * Removes the hm_buyer_pro capability from linked user(s) when their
 * Talent Buyer Pro subscription is cancelled.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function handle_stripe_customer_subscription_deleted($subscription) {
    error_log('Handle Stripe event: customer.subscription.deleted :: sub=' . ($subscription->id ?? 'unknown'));

    $customer_id = $subscription->customer ?? '';
    if (empty($customer_id)) {
        return new WP_REST_Response(['status' => 'ignored', 'reason' => 'no_customer_id'], 200);
    }

    if (!is_pro_subscription($subscription)) {
        error_log('Ignoring deleted subscription not matching PRO price: sub=' . ($subscription->id ?? 'unknown'));
        return new WP_REST_Response(['status' => 'ignored', 'reason' => 'not_pro_subscription'], 200);
    }

    $users = get_users([
        'meta_key'   => 'stripe_customer_id',
        'meta_value' => $customer_id,
        'number'     => 10,
        'fields'     => 'all',
    ]);

    if (empty($users)) {
        error_log('customer.subscription.deleted: no WP user linked to customer ' . $customer_id . ' :: sub=' . ($subscription->id ?? 'unknown'));
        return new WP_REST_Response(['status' => 'ignored', 'reason' => 'no_user_linked'], 200);
    }

    foreach ($users as $user) {
        if ($user->has_cap('hm_buyer_pro')) {
            $user->remove_cap('hm_buyer_pro');
        }

        send_subscription_cancelled_email_to_admin($user->ID, $customer_id, $subscription->id ?? '');
        error_log('Removed hm_buyer_pro from user ' . $user->ID . ', subscriber: ' . $customer_id . ', sub: ' . ($subscription->id ?? 'unknown'));
    }

    return new WP_REST_Response(['status' => 'success'], 200);
}

function is_pro_subscription($subscription) {
    // Confirm the deleted subscription is the Talent Buyer Pro monthly plan.
    // Lifetime is a one-time payment (mode: payment), so it never fires this event.
    $is_pro_subscription = false;
    if (!empty($subscription->items)) {
        foreach ($subscription->items->data as $item) {
            $price_id = isset($item->price) && is_object($item->price) ? $item->price->id : ($item->price ?? '');
            if (defined('STRIPE_PRO_PRICE_ID') && $price_id === STRIPE_PRO_PRICE_ID) {
                $is_pro_subscription = true; break;
            }
        }
    } else {
        // No items expanded; assume it is our only subscription product.
        $is_pro_subscription = true;
    }
    return $is_pro_subscription;
}
