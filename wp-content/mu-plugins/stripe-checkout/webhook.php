<?php

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
        'checkout.session.completed'    => 'handle_stripe_checkout_session_completed',
        'customer.subscription.deleted' => 'handle_stripe_customer_subscription_deleted',
    ];

    if (isset($handlers[$event->type])) {
        $result = $handlers[$event->type]($event->data->object);
        if (is_wp_error($result)) {
            error_log('Stripe webhook handler error for event ' . $event->type . ' :: id: ' . $event->id . ' :: ' . $result->get_error_message());
        }
        return $result;
    }

    error_log('Unhandled Stripe event: ' . $event->type . ' :: id: ' . $event->id);
    return new WP_REST_Response(['status' => 'ignored', 'type' => $event->type], 200);
}
