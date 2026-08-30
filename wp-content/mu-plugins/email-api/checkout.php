<?php

function send_subscription_confirmation_email($email, $product_name) {
    $subject = 'Order Confirmation — Hire Musicians';
    $manage_url = site_url('/subscriptions/');
    $message = "Hi there,\n\n";
    $message .= "Your order for " . $product_name . " was successful!\n\n";
    $message .= "To manage your subscription, visit your subscriptions page:\n";
    $message .= $manage_url . "\n\n";
    $message .= "Thanks,\nThe Hire Musicians Team";

    send_email_safely($email, $subject, $message);
}

function notify_duplicate_stripe_customer($email, $old_customer_id, $new_customer_id, $session_id) {
    $old_link = 'https://dashboard.stripe.com/customers/' . $old_customer_id;
    $new_link = 'https://dashboard.stripe.com/customers/' . $new_customer_id;

    $subject = 'Duplicate Stripe customer detected';
    $message = "A checkout produced a duplicate Stripe customer.\n\n";
    $message .= "Email: {$email}\n";
    $message .= "Session: {$session_id}\n\n";
    $message .= "Old (canonical) customer: {$old_customer_id}\n{$old_link}\n\n";
    $message .= "New customer created: {$new_customer_id}\n{$new_link}";

    send_email_to_hm_admin($subject, $message);
    error_log('Duplicate Stripe customer detected: email=' . $email . ', old=' . $old_customer_id . ', new=' . $new_customer_id . ', session=' . $session_id);
}
