<?php

function create_compensation_report($args) {

    if (!is_user_logged_in()) {
        return new WP_Error('not_logged_in', 'You must be logged in to contribute.');
    }

    $author_id = get_current_user_id();
    $args['post_title'] = "{$args['meta_input']['venue_name']} - venueID:{$args['meta_input']['venue']} - {$args['meta_input']['author_email']} - \${$args['meta_input']['total_earnings']}";

    // Create post
    $post_id = wp_insert_post($args);
    if (is_wp_error($post_id) || !$post_id) {
        return new WP_Error('creation_failed', 'Failed to create report.');
    }

    $email = $args['meta_input']['author_email'];
    send_comp_report_confirmation_email($email, $args['meta_input']['venue_name'], $author_id);

    return [
        'post_id' => $post_id,
    ];
}

function send_comp_report_confirmation_email($email, $venue_name, $author_id) {
    $subject = "Your Anonymous Live Musician Earnings Report Has Been Submitted";
    $earnings_database_url = site_url('/musician-earnings-database/');

    if (has_comp_report($author_id)) {
        $message = "Thank you for contributing your earnings data!" . "\n\n";
        $message .= "Your report for {$venue_name} has been submitted and is currently pending review." . "\n\n";
        $message .= "Your submission is completely anonymous. We will never share your name, show dates, or any other personal details." . "\n\n";
        $message .= "Once our team verifies your report, it will be included in the musician earnings database." . "\n\n";
        $message .= "You can view the database here: {$earnings_database_url}";
    } else {
        $message = "Thank you for contributing your earnings data!" . "\n\n";
        $message .= "Your report for {$venue_name} has been submitted and is currently pending review." . "\n\n";
        $message .= "Your submission is completely anonymous. We will never share your name, show dates, or any other personal details." . "\n\n";
        $message .= "Once our team verifies your report, you will be granted access to the database and will receive an email with a link to view it." . "\n\n";
    }

    send_email_safely($email, $subject, $message);
}
