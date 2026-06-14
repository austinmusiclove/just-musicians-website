<?php
/**
 * Plugin Name: Just Musicians Email API
 * Description: A custom plugin to handle all email notifications
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once 'email-api/events.php';
require_once 'email-api/compensation-reports.php';
require_once 'email-api/reviews.php';
require_once 'email-api/users.php';
require_once 'email-api/messages.php';

function send_email_safely($email, $subject, $message) {
    if (EMAIL_TEST_MODE) {
        wp_mail( ADMIN_NOTIFICATION_EMAIL, '(' . $email . ') ' . $subject, $message);
    } else {
        wp_mail($email, $subject, $message);
        wp_mail( ADMIN_NOTIFICATION_EMAIL, '(' . $email . ') ' . $subject, $message);
    }
}
