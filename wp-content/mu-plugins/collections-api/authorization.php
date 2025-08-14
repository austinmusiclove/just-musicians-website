<?php

function user_owns_collection($request) {

    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must be logged in to perform this action.', ['status' => 401]);
    }

    $user_id          = get_current_user_id();
    $user_collections = get_user_meta($user_id, 'collections', true);

    // Make sure it's an array
    if (!is_array($user_collections)) {
        $user_collections = [];
    }


    // 0 signifies user favorites
    $collection_id = $request['collection_id'];
    if ($collection_id != 0) {
        if (!in_array((int) $collection_id, array_map('intval', $user_collections), true)) {
            return new WP_Error('forbidden', 'You do not have permission to modify this collection.', ['status' => 403]);
        }
    }

    return true;
}
