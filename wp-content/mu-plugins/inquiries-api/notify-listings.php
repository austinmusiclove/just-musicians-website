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
        foreach ($listing_owners as $owner) {
            send_new_inquiry_notification($owner['email'], $inquiry_subject);
        }

    }
}

function get_listing_owners($listing_id) {
    global $wpdb;
    $confirmed_users = [];

    if (empty($listing_id) || !is_numeric($listing_id)) {
        return [];
    }

    // Prepare the serialized search pattern
    $pattern = '%:' . strlen($listing_id) . ':"' . $listing_id . '";%';

    // Query: Get user ID and email where meta_key = 'listings' and value matches
    $query = $wpdb->prepare("
        SELECT u.ID as user_id, u.user_email, um.meta_value
        FROM {$wpdb->users} u
        INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id
        WHERE um.meta_key = 'listings'
        AND um.meta_value LIKE %s
    ", $pattern);

    $results = $wpdb->get_results($query);

    foreach ($results as $row) {
        $listings = maybe_unserialize($row->meta_value);

        if (is_array($listings) && in_array((int)$listing_id, array_map('intval', $listings))) {
            $confirmed_users[] = [
                'user_id' => (int) $row->user_id,
                'email'   => $row->user_email,
            ];
        }
    }

    return $confirmed_users;
}

function send_new_inquiry_notification($email, $subject) {
    $email_subject = 'You have a new inquiry! ' . $subject;
    $message = 'Congratulations! You have a new inquiry in your inbox. Visit ' . site_url('/messages') . ' to check your messages.';
    wp_mail($email, $email_subject, $message);
}
