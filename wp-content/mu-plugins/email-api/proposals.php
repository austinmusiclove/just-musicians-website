<?php

function send_inquiry_proposal_response_email($proposal_id) {
    $event_id    = (int) get_post_meta($proposal_id, 'event', true);
    $listing_id  = (int) get_post_meta($proposal_id, 'listing', true);
    $listing_name = get_post_meta($listing_id, 'name', true);
    $event       = get_post($event_id);
    $author_id   = $event->post_author;
    $user        = get_userdata($author_id);
    $email       = $user->user_email;
    $permalink   = get_permalink($event_id);
    $subject     = 'You have a new response to your inquiry from ' . $listing_name;
    $message     = 'Congratulations! You have a new response to your inquiry. Visit ' . $permalink . ' to check your responses.';
    send_email_safely($email, $subject, $message);
}

