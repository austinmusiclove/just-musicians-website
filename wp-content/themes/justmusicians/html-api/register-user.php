<?php

$errors = array();

// User Registration
if (isset( $_POST["r_user_email"] ) && wp_verify_nonce($_POST['r_csrf'], 'r-csrf')) {
    $user_login = isset($_POST["r_user_email"]) ? $_POST['r_user_email'] : '';
    $user_email = isset($_POST["r_user_email"]) ? $_POST['r_user_email'] : '';
    $user_pass  = isset($_POST["r_user_pass"])  ? $_POST['r_user_pass']  : '';
    $remember   = isset($_POST['rememberme'])   ? $_POST['rememberme']   : false;
    $artist_invitation_code  = isset($_POST['aic']) ? $_POST["aic"] : false;
    $listing_invitation_code = isset($_POST['lic']) ? $_POST["lic"] : false;

    //if(username_exists($user_login)) {
        // Username already registered
        //array_push($errors, 'Username already taken');
    //}
    if(!validate_username($user_login)) {
        // invalid username
        array_push($errors, 'Invalid username');
    }
    if($user_login == '') {
        // empty username
        array_push($errors, 'Please enter a username');
    }
    if(!is_email($user_email)) {
        //invalid email
        array_push($errors, 'Invalid email');
    }
    if(email_exists($user_email)) {
        //Email address already registered
        array_push($errors, 'Email already registered');
    }
    if($user_pass == '') {
        // password empty
        array_push($errors, 'Please enter a password');
    }
    //if($user_pass != $pass_confirm) {
        // passwords do not match
        //array_push($errors, 'Passwords do not match');
    //}
    // Validate artist invitation code
    if ($artist_invitation_code) {
        $code_post = validate_temporary_code($artist_invitation_code);
        if (is_wp_error($code_post)) {
            if      ($code_post->get_error_code() == 'invalid_code') { array_push($errors, 'Invalid sign up link'); }
            else if ($code_post->get_error_code() == 'expired_code') { array_push($errors, 'Expired sign up link'); }
            else                                                     { array_push($errors, $code_post->get_error_message()); }
        }
    }
    // Validate listing invitation code
    if ($listing_invitation_code) {
        $code_post = validate_temporary_code($listing_invitation_code);
        if (is_wp_error($code_post)) {
            if      ($code_post->get_error_code() == 'invalid_code') { array_push($errors, 'Invalid sign up link'); }
            else if ($code_post->get_error_code() == 'expired_code') { array_push($errors, 'Expired sign up link'); }
            else                                                     { array_push($errors, $code_post->get_error_message()); }
        }
    }

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
            wp_new_user_notification($new_user_id);

            // send email verification email
            send_account_activation_link($user_email, $account_identifier);

            // log the new user in
            wp_set_auth_cookie($new_user_id, $remember);
            wp_set_current_user($new_user_id, $user_login);

            // Redirect
            if ($listing_invitation_code and $artist_invitation_code) {
                echo '<span x-init="redirect(\'/listings/?aic=' . $artist_invitation_code . '&lic=' . $listing_invitation_code . '\');"></span>';
            } else if ($artist_invitation_code) {
                echo '<span x-init="redirect(\'/listings/?aic=' . $artist_invitation_code . '\');"></span>';
            } else if ($listing_invitation_code) {
                echo '<span x-init="redirect(\'/listings/?lic=' . $listing_invitation_code . '\');"></span>';
            } else {
                echo '<span x-init="redirect();"></span>';
            }
        }
    } else {
        foreach($errors as $error) {
            echo '<div class="signup-error">'. $error . '</div>';
        }
    }
}
