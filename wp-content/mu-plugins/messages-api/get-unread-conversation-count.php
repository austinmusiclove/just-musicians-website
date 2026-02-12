<?php

function get_unread_conversation_count() {
    global $user_messages_plugin;
    return $user_messages_plugin->get_unread_conversation_count(get_current_user_id());
}
