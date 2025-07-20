<?php
function notify_listings_invited($user_id, $inquiry_id, $listing_ids, $content) {
    global $user_messages_plugin;
    if (empty($listing_ids) || !$user_id || !$inquiry_id) { return; }

    foreach ($listing_ids as $listing_id) {

        // Create or retrieve a conversation involving this user and listing
        $conversation_id = $user_messages_plugin->create_conversation([$user_id], [$listing_id]);

        // Send an empty message tied to the inquiry
        $user_messages_plugin->send_message(
            $conversation_id,
            $user_id,
            $content,
            $inquiry_id,
            null,             // No offer
            null              // No attachment
        );
    }
}

