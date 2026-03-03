<?php

function create_compensation_report($args) {

    if (!is_user_logged_in()) {
        return new WP_Error('not_logged_in', 'You must be logged in to contribute.');
    }

    $author_id          = get_current_user_id();
    $author_email       = $args['meta_input']['author_email'];
    $venue_name         = wp_unslash($args['meta_input']['venue_name']);
    $venue_post_id      = $args['meta_input']['venue_post_id'];
    $total_earnings     = $args['meta_input']['total_earnings'];
    $args['post_title'] = "{$venue_name} - venueID:{$venue_post_id} - {$author_email} - \${$total_earnings}";

    // Create post
    $post_id = wp_insert_post($args);
    if (is_wp_error($post_id) || !$post_id) {
        return new WP_Error('creation_failed', 'Failed to create report.');
    }

    // Send email notification to author
    send_comp_report_confirmation_email($author_email, $venue_name, $venue_post_id, $author_id);

    return [
        'post_id' => $post_id,
    ];
}

function send_comp_report_confirmation_email($email, $venue_name, $venue_post_id, $author_id) {
    $subject = "Your Anonymous Musician Earnings Report Has Been Submitted";
    $earnings_database_url = site_url('/musician-earnings-database/');
    $venue_url = $venue_post_id ? get_permalink($venue_post_id) : '';

    $review_invite = "";
    if ($venue_url) {
        $review_invite = "\n\n\nP.S. While your compensation report is completely anonymous, you can also leave a public review for {$venue_name} to share your experience working with them. Other musicians would love to hear about your experience! Leave a review here: {$venue_url}";
    }

    if (has_comp_report($author_id)) {
        $message = "Thank you for contributing your earnings data!" . "\n\n";
        $message .= "Your report for {$venue_name} has been submitted and is currently pending review." . "\n\n";
        $message .= "Your submission is completely anonymous. We will never share your name, show dates, or any other personal details." . "\n\n";
        $message .= "Once our team verifies your report, it will be included in the musician earnings database." . "\n\n";
        $message .= "You can view the database here: {$earnings_database_url}" . $review_invite;
    } else {
        $message = "Thank you for contributing your earnings data!" . "\n\n";
        $message .= "Your report for {$venue_name} has been submitted and is currently pending review." . "\n\n";
        $message .= "Your submission is completely anonymous. We will never share your name, show dates, or any other personal details." . "\n\n";
        $message .= "Once our team verifies your report, you will be granted access to the database and will receive an email with a link to view it." . $review_invite;
    }

    send_email_safely($email, $subject, $message);
}
