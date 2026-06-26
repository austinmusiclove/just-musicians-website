<?php
/**
 * Plugin Name: Just Musicians Inquiries API
 * Description: A custom plugin to expose REST APIs for managing inquiry posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'inquiries-api/get-user-inquiry.php'; // Deprecated
require_once 'inquiries-api/get-inquiry.php'; // Deprecated
