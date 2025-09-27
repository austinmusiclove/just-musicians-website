<?php
/**
 * Plugin Name: Hire More Musicians Reviews API
 * Description: A custom plugin to expose REST APIs for managing reviews posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'reviews-api/get-listing-reviews.php';
