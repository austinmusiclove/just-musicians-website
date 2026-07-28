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
require_once 'applications-api/authorization.php';
require_once 'applications-api/parse-args.php';
require_once 'applications-api/get-user-applications.php';
require_once 'applications-api/get-applicants.php';
require_once 'applications-api/create-application.php';
require_once 'applications-api/update-application.php';
require_once 'applications-api/delete-application.php';
require_once 'applications-api/get-musician-application-url.php';
require_once 'applications-api/get-application-submission.php';
require_once 'applications-api/get-user-application-submissions.php';
require_once 'applications-api/update-application-submission.php';
require_once 'applications-api/submit-application.php';
require_once 'applications-api/get-application-events.php';
