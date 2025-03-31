<?php

$errors = array();

// User Registration
if (isset( $_POST["r_user_email"] ) && wp_verify_nonce($_POST['r_csrf'], 'r-csrf')) {
    $user_login = isset($_POST["r_user_email"]) ? $_POST['r_user_email'] : '';
    $user_email = isset($_POST["r_user_email"]) ? $_POST['r_user_email'] : '';
    $user_pass = isset($_POST["r_user_pass"]) ? $_POST['r_user_pass'] : '';
    $remember = isset($_POST['rememberme']) ? $_POST['rememberme'] : false;

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
            do_action('wp_login', $user_login);
        }
    } else {
        foreach($errors as $error) {
            echo $error . '<br>';
        }
    }
}
