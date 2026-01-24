<?php
// Handles arg validation for compensation report apis


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


function validate_compensation_report_args() {

    // Check venue_id is filled out
    if (isset($_POST['venue_id']) and empty($_POST['venue_id'])) {
        return new WP_Error('missing_required_field_venue_id', 'Missing required field: Venue');
    }

    // Check Total Earnings has a value
    if (isset($_POST['total_earnings']) && $_POST['total_earnings'] === '') {
        return new WP_Error('missing_required_field_total_earnings', 'Missing required field: Total Earnings');
    }

    // Check Performance Duration has a value
    if (isset($_POST['minutes_performed']) && $_POST['minutes_performed'] === '') {
        return new WP_Error('missing_required_field_minutes_performed', 'Missing required field: Performance Duration');
    }

    // Check Performance Duration has a value greater than 0
    if ($_POST['minutes_performed'] <= 0) {
        return new WP_Error('out_of_bounds_field_minutes_performed', 'Performance Duration must be greater than 0');
    }

    // Check Num Performers has a value
    if (isset($_POST['total_performers']) && $_POST['total_performers'] === '') {
        return new WP_Error('missing_required_field_total_performers', 'Missing required field: Number of Performers');
    }

    // Check Num Performers has a value greater than 0
    if ($_POST['total_performers'] <= 0) {
        return new WP_Error('out_of_bounds_field_total_performers', 'Number of Performers must be greater than 0');
    }

    // Check Comp Structure has a value
    if (isset($_POST['comp_structure']) and empty($_POST['comp_structure'])) {
        return new WP_Error('missing_required_field_comp_structure', 'Missing required field: Compensation Structure');
    }

    // Check Payment Speed has a value
    if (isset($_POST['payment_speed']) and empty($_POST['payment_speed'])) {
        return new WP_Error('missing_required_field_payment_speed', 'Missing required field: Payment Speed');
    }

    // Check Payment Method has a value
    if (isset($_POST['payment_method']) and empty($_POST['payment_method'])) {
        return new WP_Error('missing_required_field_payment_method', 'Missing required field: Payment Method');
    }

    // Check Performing Act Name has a value
    if (isset($_POST['performing_act_name']) and empty($_POST['performing_act_name'])) {
        return new WP_Error('missing_required_field_performing_act_name', 'Missing required field: Performing Act Name');
    }

    // Check Performance Date has a value
    if (isset($_POST['performance_date']) and empty($_POST['performance_date'])) {
        return new WP_Error('missing_required_field_performance_date', 'Missing required field: Performance Date');
    }

    // Check Performance Date is in the past
    if (!empty($_POST['performance_date'])) {
        try {
            $performance_date = new DateTime($_POST['performance_date']);
            $today = new DateTime('today');
            if ($performance_date >= $today) {
                return new WP_Error( 'invalid_performance_date', 'Performance Date must be in the past');
            }
        } catch (Exception $e) {
            return new WP_Error( 'invalid_performance_date_format', 'Invalid Performance Date format');
        }
    }

    return;
}
