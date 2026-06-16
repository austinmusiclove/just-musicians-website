<?php
/**
 * Plugin Name: Just Musicians Proposals API
 * Description: A custom plugin to expose REST APIs for managing proposal posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once 'proposals-api/authorization.php';
require_once 'proposals-api/create-proposal.php';
require_once 'proposals-api/get-user-listing-proposals.php';
require_once 'proposals-api/get-proposals-with-data.php';
require_once 'proposals-api/request-proposal.php';
