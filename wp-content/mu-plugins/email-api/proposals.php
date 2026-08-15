<?php

function send_inquiry_proposal_response_email($user_id, $proposal_id, $event_id) {
    $listing_id  = (int) get_post_meta($proposal_id, 'listing', true);
    $listing_name = get_post_meta($listing_id, 'name', true);
    $permalink   = get_permalink($event_id);
    $user        = get_userdata($user_id);
    $email       = $user->user_email;
    $subject     = 'You have a new response to your inquiry from ' . $listing_name;
    $message     = 'Congratulations! You have a new response to your inquiry. Visit ' . $permalink . ' to check your responses.';
    send_email_safely($email, $subject, $message);
}

function send_proposal_date_time_change_email($user_id, $listing_id, $event_id) {
    $listing_name = get_post_meta($listing_id, 'name', true);
    $event_name   = get_the_title($event_id);
    $link         = site_url('/my-gigs/?status=request');
    $user         = get_userdata($user_id);
    $email        = $user->user_email;
    $subject      = 'Date or time changed for ' . $event_name . '. Update availability for ' . $listing_name;
    $message      = 'The date or time for ' . $event_name . ' has changed. Visit ' . $link . ' to update availability for ' . $listing_name . '.';
    send_email_safely($email, $subject, $message);
}

function send_proposal_request_email($user_id, $listing_id, $event_id) {
    $user = get_userdata($user_id);
    $email = $user->user_email;
    $link = site_url('/my-gigs/?status=request');
    $event_name = get_post_meta($event_id, 'event_name', true);
    $listing_name = get_post_meta($listing_id, 'name', true);
    $subject = 'New inquiry for ' . $listing_name . ' - ' . $event_name;
    $message = 'Congratulations! There is a new inquiry in your inbox for ' . $listing_name . '. Visit ' . $link . ' to review the details and respond.';
    send_email_safely($email, $subject, $message);
}

