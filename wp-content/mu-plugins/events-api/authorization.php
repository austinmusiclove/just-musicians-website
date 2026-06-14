<?php

function user_owns_event($request) {

    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must be logged in to perform this action.', ['status' => 401]);
    }

    if (!isset($request['event_id']) || !is_numeric($request['event_id'])) {
        return new WP_Error('invalid_event_id', 'Event ID is required and must be an integer.', ['status' => 400]);
    }

    $user_id = get_current_user_id();
    $event_author_id = get_post_field('post_author', $request['event_id']);

    return $user_id == $event_author_id;
}
