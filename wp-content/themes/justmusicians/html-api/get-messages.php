<?php
global $user_messages_plugin;

// Get Messages
$page = $_GET['page'] ?? 1;
$user_id = get_current_user_id();
$conversation_id = get_query_var('conversation-id') ?? 0;
if (!$conversation_id) {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'Failed to get messages for this conversation\'})"></span>'; exit;
}
$messages = $user_messages_plugin->get_conversation_messages($conversation_id);

/*
$max_num_results = $result['max_num_results'];
$max_num_pages   = $result['max_num_pages'];
$is_last_page    = $page == $max_num_pages;
$next_page       = $result['next_page'];
*/

if (count($messages) > 0) {

    // Traverse messages in reverse to display in chronological order
    for ($iter = count($messages) - 1; $iter >= 0; $iter--) {
        $message = $messages[$iter];
        echo get_template_part('template-parts/messages/basic-message', '', [
            'content'      => $message->content,
            'is_outgoing'  => $message->sender_id == $user_id,
            //'last'       => $i === 0,
            //'is_last_page' => $is_last_page,
            //'next_page'    => $next_page,
        ]);
    }

} else {
    echo get_template_part('template-parts/messages/no-messages', '', []);
}
