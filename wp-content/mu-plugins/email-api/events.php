<?php

function send_creator_new_event_email($user_id, $event_name, $event_link) {
    $user_data = get_userdata($user_id);
    $owner_email = $user_data->user_email;
    $subject = 'Your event has been created!';
    $message = 'Thank you for creating an event on Hire Musicians. You can edit your event and see responses from musicians here: ' . $event_link;
    send_email_safely($owner_email, $subject, $message);
}

function send_admin_new_event_email($user_id, $event_name) {
    $user_data = get_userdata($user_id);
    $owner_email = $user_data->user_email;
    $message = 'New event has been created by ' . $owner_email . '. :: ' . $event_name;
    send_email_to_hm_admin('New Event by ' . $owner_email, $message);
}
