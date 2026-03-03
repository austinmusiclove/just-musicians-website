<?php

add_action('transition_post_status', 'notify_author_on_comp_report_publish', 10, 3);

function notify_author_on_comp_report_publish($new_status, $old_status, $post) {
    if ($post->post_type !== 'comp_report') {
        return;
    }

    if ($new_status === 'publish' && $old_status == 'pending') {

        $author_email = get_post_meta($post->ID, 'author_email', true);
        if (!$author_email) {
            return;
        }

        $subject = "Your Musician Earnings Report Has Been Verified";
        $earnings_database_url = site_url('/musician-earnings-database/');
        $venue_name = get_post_meta($post->ID, 'venue_name', true);
        $message = "Great news! Your earnings report for {$venue_name} has been verified and is now live in the musician earnings database." . "\n\n";
        $message .= "Your submission is completely anonymous. We will never share your name, show dates, or any other personal details." . "\n\n";
        $message .= "You can view the database here: {$earnings_database_url}";

        send_email_safely($author_email, $subject, $message);
    }
}
