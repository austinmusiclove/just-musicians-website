<?php

// used for tracking error messages
function r_errors(){
    static $wp_error; // global variable handle
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// User Registration
if (isset( $_POST["r_user_email"] ) && wp_verify_nonce($_POST['r_csrf'], 'r-csrf')) {
    $user_login = isset($_POST["r_user_email"]) ? $_POST['r_user_email'] : '';
    $user_email = isset($_POST["r_user_email"]) ? $_POST['r_user_email'] : '';
    $user_pass = isset($_POST["r_user_pass"]) ? $_POST['r_user_pass'] : '';
    $remember = isset($_POST['rememberme']) ? $_POST['rememberme'] : false;

    if(username_exists($user_login)) {
        // Username already registered
        r_errors()->add('username_unavailable', __('Username already taken'));
    }
    if(!validate_username($user_login)) {
        // invalid username
        r_errors()->add('username_invalid', __('Invalid username'));
    }
    if($user_login == '') {
        // empty username
        r_errors()->add('username_empty', __('Please enter a username'));
    }
    if(!is_email($user_email)) {
        //invalid email
        r_errors()->add('email_invalid', __('Invalid email'));
    }
    if(email_exists($user_email)) {
        //Email address already registered
        r_errors()->add('email_used', __('Email already registered'));
    }
    if($user_pass == '') {
        // password empty
        r_errors()->add('password_empty', __('Please enter a password'));
    }
    //if($user_pass != $pass_confirm) {
        // passwords do not match
        //r_errors()->add('password_mismatch', __('Passwords do not match'));
    //}

    $errors = r_errors()->get_error_messages();

    // if no errors then cretate user
    if(empty($errors)) {
        $account_identifier = md5(rand()); // secret used to verifiy email
        $new_user_id = wp_insert_user(array(
                'user_login' => $user_login,
                'user_pass' => $user_pass,
                'user_email' => $user_email,
                'user_registered' => date('Y-m-d H:i:s'),
                'role' => 'subscriber',
                'meta_input' => array('email_verified' => false, 'account_identifier' => $account_identifier)
            )
        );
        if($new_user_id) {
            // send an email to the admin
            //wp_new_user_notification($new_user_id);

            // send email verification email
            send_account_activation_link($user_email, $account_identifier);

            // log the new user in
            wp_set_auth_cookie($new_user_id, $remember);
            wp_set_current_user($new_user_id, $user_login);
            do_action('wp_login', $user_login);

            // send the newly created user to the home page after logging them in
            //wp_redirect(get_site_url()); exit;
            echo 'Success';
        }
    } else {
        echo 'Failure';
    }
}
