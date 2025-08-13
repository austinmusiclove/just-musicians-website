<?php
function notify_listings_invited($user_id, $inquiry_id, $listing_ids, $inquiry_subject) {
    global $user_messages_plugin;
    if (empty($listing_ids) || !$user_id || !$inquiry_id) { return; }

    foreach ($listing_ids as $listing_id) {

        // Create or retrieve a conversation involving this user and listing
        $conversation_id = $user_messages_plugin->create_conversation([$user_id], [$listing_id]);

        // Send an empty message tied to the inquiry
        $user_messages_plugin->send_message(
            $conversation_id,
            $user_id,
            $inquiry_subject, // Message content
            $inquiry_id,
            null,             // No offer
            null              // No attachment
        );

        // Notify all listing owners via email
        $listing_owners = get_listing_owners($listing_id);
        foreach ($listing_owners as $user_id) {
            send_new_inquiry_notification($user_id, $inquiry_subject);
        }

    }
}

function send_new_inquiry_notification($user_id, $message_subject) {
    $user = get_userdata($user_id);
    $email = $user->user_email;
    $subject = 'You have a new inquiry! ' . $message_subject;
    $message = 'Congratulations! You have a new inquiry in your inbox. Visit ' . site_url('/messages') . ' to check your messages.';
    if (EMAIL_TEST_MODE) {
        wp_mail( ADMIN_NOTIFICATION_EMAIL, '(' . $email . ') ' . $subject, $message);
    } else {
        wp_mail($email, $subject, $message);
    }
}
