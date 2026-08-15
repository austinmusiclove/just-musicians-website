<?php

function send_listing_owner_review_invite_email($recipient_user_id, $author_name, $listing_name, $buyer_review_url) {
    $recipient = get_userdata($recipient_user_id);
    $email = $recipient->user_email;
    $subject = "You have a new listing review!";
    $message = "Congratulations, {$author_name} has written a review for your listing, {$listing_name}. If you'd like, you can follow this link to write them a review back: {$buyer_review_url}";
    send_email_safely($email, $subject, $message);
}

function send_sign_up_to_see_review_email($listing_id, $author_name) {
    $listing_email = get_post_meta($listing_id, 'email', true);
    $listing_name = get_post_meta($listing_id, 'name', true);
    $expiration = time() + 31536000; // one year
    $tmp_code = create_temporary_code($expiration, [ 'listings' => [$listing_id] ]);
    if (is_wp_error($tmp_code)) {
        send_failed_to_generate_tmp_code_email( 'Listing email: ' . $listing_email . "\n" . 'Listing Name: ' . $listing_name . "\n" . 'Author Name: ' . $author_name . "\n", $tmp_code);
        return;
    }
    $sign_up_link = site_url('/listings') . '?lic=' . $tmp_code . '&mdl=signup';
    $subject = 'You have a new review!';
    $message = "Congratulations, {$author_name} has written a review for your listing, {$listing_name}. Sign up for a free account to claim your listing and read your review: {$sign_up_link}";
    send_email_safely($listing_email, $subject, $message);
}
