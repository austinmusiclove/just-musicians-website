<?php
// Handles arg validation for compensation report apis


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


function validate_compensation_report_args() {

    // Cover image
    /*
    if (!isset($_POST['cover_image_meta'])) {
        return new WP_Error('missing_cover_image', 'Cover image required');
    } else {
        $cover_image_data = custom_parse_file($_POST['cover_image_meta'], 'cover_image');
        if (empty($cover_image_data['attachment_id']) and empty($cover_image_data['file'])) {
            return new WP_Error('missing_cover_image', 'Cover image required');
        }
    }
     */

    // State
    /*
    if (isset($_POST['state']) and empty($_POST['state'])) {
        return new WP_Error('missing_state', 'State required');
    }
     */

    // Email
    /*
    if (isset($_POST['listing_email']) and !empty($_POST['listing_email'])) {
        if (!filter_var($_POST['listing_email'], FILTER_VALIDATE_EMAIL)) {
            return new WP_Error('invalid_email', 'Invalid email format');
        }
    }
     */

    // Phone
    /*
    if (isset($_POST['phone']) and !empty($_POST['phone'])) {
        $phone_pattern = '/^\(\d{3}\) \d{3}-\d{4}$/';
        if (!preg_match($phone_pattern, $_POST['phone'])) {
            return new WP_Error('invalid_phone', 'Phone must be in the format (555) 555-5555');
        }
    }
     */


    return;
}
