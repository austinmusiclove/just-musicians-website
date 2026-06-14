<?php

function send_new_message_email($user_id) {
    $user = get_userdata($user_id);
    $email = $user->user_email;
    $subject = 'You have a new message!';
    $message = 'You have a new message in your inbox. Visit ' . site_url('/messages') . ' to check your messages.';
    send_email_safely($email, $subject, $message);
}
