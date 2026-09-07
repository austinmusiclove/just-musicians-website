<?php
/**
 * Plugin Name: Hire More Musicians Collections API
 * Description: A custom plugin to expose REST APIs for managing collection posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'collections-api/authorization.php';
require_once 'collections-api/get-collections.php';
require_once 'collections-api/create-collection.php';
require_once 'collections-api/delete-collection.php';
require_once 'collections-api/add-listing-to-collection.php';
require_once 'collections-api/remove-listing-from-collection.php';
require_once 'collections-api/reorder-collection-listings.php';
