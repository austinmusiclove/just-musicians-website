<?php

// Password reset email
function custom_password_reset_email($message, $key, $user_login, $user_data) {
    $reset_url = add_query_arg(array(
        'login' => rawurlencode($user_login),
        'key' => $key
    ), site_url('password-reset'));

    // Customize your email message
    $message = "Hello, $user_login\n\n";
    $message .= "We received a request to reset your password. Please click the link below to reset your password:\n\n";
    $message .= $reset_url . "\n\n";
    $message .= "If you didn't request this, you can safely ignore this email.\n\n";
    $message .= "Thanks,\nJust Musicians";

    return $message;
}
add_filter('retrieve_password_message', 'custom_password_reset_email', 10, 4);


// Login redirect
function custom_login_failed($username) {
    echo '<div class="login-error">Invalid login credentials. Please try again.</div>'; // TODO handle errors properly
    exit;  // Stop further processing
}
add_action('wp_login_failed', 'custom_login_failed');
function custom_login_success($username) {
    echo '<span x-init="redirectHome();"></span>';
    exit;  // Stop further processing
}
add_action('wp_login', 'custom_login_success');
