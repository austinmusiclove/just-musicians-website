<?php

function send_listing_owner_review_invite_email($recipient_user_id, $author_name, $listing_name, $buyer_review_url) {
    $recipient = get_userdata($recipient_user_id);
    $email = $recipient->user_email;
    $subject = "You have a new listing review!";
    $message = "Congratulations, {$author_name} has written a review for your listing, {$listing_name}. If you'd like, you can follow this link to write them a review back: {$buyer_review_url}";
    send_email_safely($email, $subject, $message);
}
