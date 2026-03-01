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

    // Send email to author
    $email = $args['meta_input']['author_email'];
    $subject = "Your Anonymous Live Musician Earnings Report Has Been Submitted";
    $earnings_database_url = site_url('/musician-earnings-database/');
    $message = "Thank you for contributing your earnings data!" . "\n\n";
    $message .= "Your report for {$args['meta_input']['venue_name']} has been submitted and is currently pending review." . "\n\n";
    $message .= "Your submission is completely anonymous. We will never share your name, show dates, or any other personal details." . "\n\n";
    $message .= "Once our team verifies the authenticity of your report, it will be included in the musician earnings database." . "\n\n";
    $message .= "You can view the database here: {$earnings_database_url}";
    send_email_safely($email, $subject, $message);

    return [
        'post_id' => $post_id,
    ];
}
