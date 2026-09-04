<?php

function send_admin_claim_listing_email($user_id, $listing_id) {
    $user    = get_userdata($user_id);
    $email   = $user->user_email;
    $subject = 'Listing Claim Request';
    $message = "A listing claim request was submitted by $email" . PHP_EOL
        . 'Listing ID: ' . $listing_id . PHP_EOL
        . 'Listing URL: ' . get_permalink($listing_id);
    send_email_to_hm_admin($subject, $message);
}
