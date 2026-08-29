<?php

// Password reset email
function custom_password_reset_email($message, $key, $user_login, $user_data) {
    $reset_url = add_query_arg(array(
        'login' => rawurlencode($user_login),
        'key' => $key
    ), site_url('password-reset'));

    $display_name = !empty($user_data->display_name) ? $user_data->display_name : $user_login;

    // Customize your email message
    $message = "Hello, $display_name\n\n";
    $message .= "Please click the link below to set your password and log in:\n\n";
    $message .= $reset_url . "\n\n";
    $message .= "If you didn't request this, you can safely ignore this email.\n\n";
    $message .= "Thanks,\nHire Musicians Team";

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
    // Check if the referer was the default wp-login.php (backend login)
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    if (strpos($referer, 'wp-login.php') !== false || strpos($referer, 'wp-admin') !== false) {
        return; // Skip if it's the default WP login page or wp-admin
    }

    echo '<span x-init="redirect();"></span>';
    exit;  // Stop further processing
}
add_action('wp_login', 'custom_login_success');
