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
