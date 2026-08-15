<?php

function send_creator_new_application_email($user_id, $application_id) {
    $permalink = get_permalink($application_id);
    $user_data = get_userdata($user_id);
    $email = $user_data->user_email;
    $subject = 'Your application has been created!';
    $message = 'Thank you for creating an application on Hire Musicians. You can edit your application and review your applicants here: ' . $permalink;
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

function send_application_submitted_successfully_email($user_id, $application_id) {
    $application_title = get_post_meta($application_id, 'title', true);
    $user_data = get_userdata($user_id);
    $email = $user_data->user_email;
    $subject = 'Your application submission was successful!';
    $message = "Your application submission for $application_title was received.";
    send_email_safely($email, $subject, $message);
}

function send_sign_up_to_complete_application_email($email, $application_id, $sign_up_link) {
    $application_title = get_post_meta($application_id, 'title', true);
    $subject = 'Your application submission has been submitted.';
    $message = "We have received your application submission for " . $application_title
        . "We need to verify you are a real person for the reviewer. Please create an account to complete your submission.\n\n"
        . "Create your free account here:\n\n"
        . $sign_up_link . "\n\n"
        . "When you sign up through this link, we'll automatically connect your application submission and new musician listing to your new account. Please reply to this email for support if you run into any issues.\n\n"
        . "See you on the stage,\nThe Hire Musicians Team";
    send_email_safely($email, $subject, $message);
}
