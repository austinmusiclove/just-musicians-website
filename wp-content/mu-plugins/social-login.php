<?php
/**
 * Plugin Name: Social Login
**/

// Reference
// https://github.com/ruvictor/wp-login-google/blob/master/login.php
// https://www.youtube.com/watch?v=D3nM2sjRwpM

//* Don't access this file directly
defined( 'ABSPATH' ) or die();

/**
 * Google App Configuration
 **/
// call sdk library
require_once 'google-api/vendor/autoload.php';

$GOOGLE_CLIENT_ID = JM_GOOGLE_WEB_OAUTH_CLIENT_ID;
$GOOGLE_CLIENT_SECRET = JM_GOOGLE_WEB_OAUTH_CLIENT_SECRET;
$GOOGLE_REDIRECT_URI = JM_GOOGLE_WEB_OAUTH_REDIRECT_URI;
$GOOGLE_APPLICATION_NAME = "Just Musicians";
$GOOGLE_SCOPES = "https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email";

$gClient = new Google_Client();
$gClient->setClientId($GOOGLE_CLIENT_ID);
$gClient->setClientSecret($GOOGLE_CLIENT_SECRET);
$gClient->setApplicationName($GOOGLE_APPLICATION_NAME);
$gClient->setRedirectUri($GOOGLE_REDIRECT_URI);
$gClient->addScope($GOOGLE_SCOPES);
$gClient->setState(sanitize_text_field($_SERVER['REQUEST_URI']));

// login URL
$login_url = $gClient->createAuthUrl();

// generate button shortcode
add_shortcode('google-login', 'login_with_google');
function login_with_google(){
    global $login_url;
    return $login_url;
}

// add ajax action
add_action('wp_ajax_login_google', 'login_google');
function login_google(){
    // echo "fffff";
    global $gClient;
    // checking for google code
    if (isset($_GET['code'])) {
        $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
        if(!isset($token["error"])){
            // get data from google
            $oAuth = new Google_Service_Oauth2($gClient);
            $userData = $oAuth->userinfo_v2_me->get();
        }

        // check if user email already registered
        if(!email_exists($userData['email'])){
            // generate password
            $bytes = openssl_random_pseudo_bytes(2);
            $password = md5(bin2hex($bytes));
            $user_login = $userData['id'];


            $new_user_id = wp_insert_user(array(
                'user_login'		=> $user_login,
                'user_pass'	 		=> $password,
                'user_email'		=> $userData['email'],
                'first_name'		=> $userData['givenName'],
                'last_name'			=> $userData['familyName'],
                'user_registered'	=> date('Y-m-d H:i:s'),
                'role'				=> 'subscriber',
                'meta_input' => array(
                  'account_identifier' => md5(rand()),
                  'email_verified' => true
                  )
                )
            );
            if($new_user_id) {
                // send an email to the admin
                wp_new_user_notification($new_user_id);

                // log the new user in
                wp_set_current_user($new_user_id);
                wp_set_auth_cookie($new_user_id, true);
                //do_action('wp_login', $user_login, $userData['email']);

                // send the newly created user to the home page after login
                wp_redirect(site_url($_GET['state'])); exit;
            }
        }else{
            //if user already registered than we are just loggin in the user
            $user = get_user_by( 'email', $userData['email'] );
            wp_set_auth_cookie($user->ID, true);
            wp_set_current_user($user->ID, $user->user_login);
            //do_action('wp_login', $user->user_login, $user);
            wp_redirect(site_url($_GET['state'])); exit;
        }


    }else{
        wp_redirect(home_url());
        exit();
    }
}

// ALLOW LOGGED OUT users to access admin-ajax.php action
function add_google_ajax_actions(){
    add_action('wp_ajax_nopriv_login_google', 'login_google');
}
add_action('admin_init', 'add_google_ajax_actions');
