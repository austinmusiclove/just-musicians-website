<?php

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

function send_creator_new_event_email($user_id, $event_name, $event_link) {
    $user_data = get_userdata($user_id);
    $owner_email = $user_data->user_email;
    $subject = 'Your event has been created!';
    $message = 'Thank you for creating an event on HireMusicians.com. You can edit your event and see responses from musicians here: ' . $event_link;
    send_email_safely($owner_email, $subject, $message);
}

function send_admin_new_event_email($user_id, $event_name) {
    $user_data = get_userdata($user_id);
    $owner_email = $user_data->user_email;
    $message = 'New event has been created by ' . $owner_email . '. :: ' . $event_name;
    wp_mail(ADMIN_NOTIFICATION_EMAIL, 'New Event by ' . $owner_email, $message);
}
