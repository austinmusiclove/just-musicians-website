<?php

function user_owns_inquiry($request) {

    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must be logged in to perform this action.', ['status' => 401]);
    }

    $user_id          = get_current_user_id();
    $user_inquiries = get_user_meta($user_id, 'inquiries', true);

    // Make sure it's an array
    if (!is_array($user_inquiries)) {
        $user_inquiries = [];
    }


    // 0 signifies user favorites
    $inquiry_id = $request['inquiry_id'];
    if (!in_array((int) $inquiry_id, array_map('intval', $user_inquiries), true)) {
        return new WP_Error('forbidden', 'You do not have permission to modify this inquiry.', ['status' => 403]);
    }

    return true;
}
