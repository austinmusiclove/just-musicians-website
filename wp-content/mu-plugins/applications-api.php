<?php
/**
 * Plugin Name: Just Musicians Applications API
 * Description: A custom plugin to expose REST APIs for managing application posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'applications-api/get-user-applications.php';
