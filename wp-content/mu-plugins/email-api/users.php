<?php

function send_account_activation_email($email, $account_identifier) {
    $link = site_url() . "/email-verification/?aci=" . $account_identifier;
    $subject = 'Verify your email to activate your Hire More Musicians account';
    $message = 'Thank you for creating an account with Hire More Musicians. Please click this link to verify your email and activate you account: ' . $link;
    wp_mail($email, $subject, $message);
}
