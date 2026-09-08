<?php

function require_collection_access($collection_id, $allowed_access_types) {

    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must sign in to perform this function.');
    }

    // 0 signifies user favorites; the current user always owns their own favorites
    if ((int) $collection_id == 0) {
        return true;
    }

    if (!isset($collection_id) || !is_numeric($collection_id)) {
        return new WP_Error('invalid_collection_id', 'Collection ID is required and must be an integer.', ['status' => 400]);
    }

    if (current_user_can('manage_options')) {
        return true;
    }

    $user_id = get_current_user_id();

    foreach ($allowed_access_types as $access_type) {
        if (hm_user_has_access($user_id, (string) $collection_id, $access_type, 'collection')) {
            return true;
        }
    }

    return new WP_Error('unauthorized_user', 'Your account is not authorized for this resource', ['status' => 400]);
}

function user_can_view_collection($collection_id) { return require_collection_access($collection_id, [HM_ACCESS_TYPE_VIEW, HM_ACCESS_TYPE_EDIT, HM_ACCESS_TYPE_OWNER]); }
function user_owns_collection($collection_id)     { return require_collection_access($collection_id, [HM_ACCESS_TYPE_OWNER]); }
function user_can_edit_collection($collection_id) { return require_collection_access($collection_id, [HM_ACCESS_TYPE_EDIT, HM_ACCESS_TYPE_OWNER]); }
