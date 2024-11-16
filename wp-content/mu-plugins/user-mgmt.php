<?php
/**
 * Plugin Name: User Management
**/

// User Registration
function add_new_user() {
    $user_email = isset($_POST["user_email"]) ? $_POST["user_email"] : '';
    $user_pass = isset($_POST["user_pass"]) ? $_POST["user_pass"] : '';
    $pass_confirm = isset($_POST["user_pass_conf"]) ? $_POST["user_pass_conf"] : '';
    $remember = isset($_POST['rememberme']) ? $_POST["rememberme"] : false;
    $errors = array();

    // Username already registered
    if(username_exists($user_email)) {
        array_push($errors, __('Username already taken'));
    }
    // invalid username
    if(!validate_username($user_email)) {
        array_push($errors, __('Invalid username'));
    }
    // empty username
    if($user_email == '') {
        array_push($errors, __('Please enter an email'));
    }
    //invalid email
    if(!is_email($user_email)) {
        array_push($errors, __('Invalid email'));
    }
    //Email address already registered
    if(email_exists($user_email)) {
        array_push($errors, __('Email already registered'));
    }
    // password empty
    if($user_pass == '') {
        array_push($errors, __('Please enter a password'));
    }
    // passwords do not match
    if($user_pass != $pass_confirm) {
        array_push($errors, __('Passwords do not match'));
    }

    // if no errors then cretate user
    if(empty($errors)) {
        $account_identifier = md5(rand()); // secret used to verifiy email
        $new_user_id = wp_insert_user(array(
                'user_login' => $user_email,
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
            wp_set_current_user($new_user_id, $user_email);
            do_action('wp_login', $user_email);
        } else {
            array_push($errors, $new_user_id);
        }
        return;
    }
    return new WP_Error(500, 'User Registration failed', array('status' => 500, 'errors' => $errors));
}
function jm_errors(){
    static $wp_error; // global variable handle
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}
function send_account_activation_link($email, $account_identifier) {
    $link = site_url() . "/email-verification/?aci=" . $account_identifier;
    $message = 'Thank you for creating an account with Just Musicians. Please click this link to verify your email and activate you account: ' . $link;
    wp_mail($email, 'Verify your email to activate your Just Musicians account', $message);
}

// Called by email verification page that is linked in the email sent to new users
function activate_account($account_identifier) {
    // get user
    $account_user = get_users(array(
        'meta_key' => 'account_identifier',
        'meta_value' => $account_identifier
    ));
    // update the user email_verified field
    $update_success = update_user_meta($account_user[0]->ID, "email_verified", true);
    if ($update_success == true) {
        return "Your email has been verified. You may now close this browser tab.";
    } else {
        return "Error updating account record. Please contact the admin for assistance on your subscription at john@justmusicians.com";
    }
}

// Rest APIs
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'users/register', [
        'methods' => 'POST',
        'callback' => 'add_new_user',
    ]);
});
