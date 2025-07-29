<?php

function remove_read_receipt(WP_REST_Request $request) {
    global $user_messages_plugin;
    $message_id = $request['message_id'];
    $user_id = $request['user_id'];

    // Mark message as read
    return $user_messages_plugin->remove_read_receipt($message_id, $user_id);
}
