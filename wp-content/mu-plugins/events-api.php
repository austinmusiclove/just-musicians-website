<?php
/**
 * Plugin Name: Just Musicians Events API
 * Description: A custom plugin to expose REST APIs for managing event posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'events-api/authorization.php';
require_once 'events-api/parse-args.php';
#require_once 'inquiries-api/get-user-inquiry.php';
#require_once 'inquiries-api/get-inquiry.php';
require_once 'events-api/get-user-events.php';
require_once 'events-api/create-event.php';
#require_once 'inquiries-api/update-user-inquiry.php';
require_once 'events-api/notify-listing-proposal-request.php';
