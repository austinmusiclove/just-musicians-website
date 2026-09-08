<?php

function send_access_granted_email($email, $subject_id, $subject_type, $access_type) {
    $title     = get_the_title($subject_id);
    $permalink = get_permalink($subject_id);

    $subject = 'You have been granted access: ' . $title;
    $message = "Hi,\n\n"
        . "You have been granted {$access_type} access to \"{$title}\" on Hire Musicians.\n\n"
        . "View it here: {$permalink}\n\n"
        . "Thanks,\nThe Hire Musicians Team";

    send_email_safely($email, $subject, $message);
}

function send_access_invite_email($email, $subject_id, $subject_type, $access_type) {
    $title     = get_the_title($subject_id);
    $permalink = get_permalink($subject_id);

    $subject = 'You have been invited to access: ' . $title;
    $message = "Hi,\n\n"
        . "You have been granted {$access_type} access to \"{$title}\" on Hire Musicians.\n\n"
        . "You'll need a free account to view it. Your access will be applied automatically when you sign up with {$email}.\n\n"
        . "Create your free account here: " . home_url() . "\n\n"
        . "Once you're signed in, you can view it here: {$permalink}\n\n"
        . "Thanks,\nThe Hire Musicians Team";

    send_email_safely($email, $subject, $message);
}
