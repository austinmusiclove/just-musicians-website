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

        $venue_name = get_post_meta($post->ID, 'venue_name', true);
        send_author_comp_report_verified_email($author_email, $venue_name);
    }
}
