<?php
/**
 * Plugin Name: Just Musicians Proposals API
 * Description: A custom plugin to expose REST APIs for managing proposal posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once 'proposals-api/create-proposal.php';
