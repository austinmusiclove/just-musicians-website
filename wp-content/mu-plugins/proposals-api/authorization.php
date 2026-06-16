<?php
if (!defined('ABSPATH')) { exit; }

function user_can_create_inquiry_proposal($event_id) {
    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must be logged in.', ['status' => 401]);
    }

    if (!$event_id || !is_numeric($event_id)) {
        return new WP_Error('invalid_event_id', 'Event ID is required.', ['status' => 400]);
    }

    $user_id   = get_current_user_id();
    $author_id = (int) get_post_field('post_author', $event_id);

    if ((int) $user_id !== $author_id) {
        return new WP_Error('forbidden', 'You are not the author of this event.', ['status' => 403]);
    }

    return true;
}
