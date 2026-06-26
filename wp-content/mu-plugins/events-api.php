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
require_once 'events-api/update-event.php';
require_once 'events-api/handle-event-date-time-change.php';
require_once 'events-api/delete-event.php';
