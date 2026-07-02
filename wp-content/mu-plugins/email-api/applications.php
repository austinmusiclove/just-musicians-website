<?php

function send_creator_new_application_email($user_id, $application_id) {
    $permalink = get_permalink($application_id);
    $user_data = get_userdata($user_id);
    $email = $user_data->user_email;
    $subject = 'Your application has been created!';
    $message = 'Thank you for creating an application on HireMusicians.com. You can edit your application and review your applicants here: ' . $permalink;
    send_email_safely($email, $subject, $message);
}

function send_creator_new_applicant_email($user_id, $application_id) {
    $permalink = esc_url(add_query_arg('tab', 'applicants', get_permalink($application_id)));
    $application_title = get_post_meta($application_id, 'title', true);
    $user_data = get_userdata($user_id);
    $email = $user_data->user_email;
    $subject = 'You have a new applicant!';
    $message = "You have a new applicant for your application, $application_title. You can review your applicants here: $permalink";
    send_email_safely($email, $subject, $message);
}
