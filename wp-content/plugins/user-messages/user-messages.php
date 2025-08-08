<?php

/*
 * Plugin Name: User Messages
 * Version: 1.0.0
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once 'queries/conversations-queries.php';


class UserMessagesPlugin {
    private $charset;
    private $tables;

    function __construct() {
        global $wpdb;
        $this->charset = $wpdb->get_charset_collate();
        $prefix = $wpdb->prefix;
        $this->tables = [
            'messages'                  => $prefix . 'um_messages',
            'conversations'             => $prefix . 'um_conversations',
            'conversation_participants' => $prefix . 'um_conversation_participants',
            'read_receipts'             => $prefix . 'um_read_receipts',
        ];
        add_action('activate_user-messages.php', [$this, 'onActivate']);
    }

    function onActivate() {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        global $wpdb;
        $tables = $this->tables;

        dbDelta("CREATE TABLE {$tables['conversations']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            participant_hash CHAR(64) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uniq_participant_hash (participant_hash)
        ) {$this->charset};");

        dbDelta("CREATE TABLE {$tables['conversation_participants']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            conversation_id BIGINT UNSIGNED NOT NULL,
            user_id BIGINT UNSIGNED DEFAULT NULL,
            listing_id BIGINT UNSIGNED DEFAULT NULL,
            PRIMARY KEY (id),
            INDEX idx_conv_user (conversation_id, user_id),
            INDEX idx_conv_listing (conversation_id, listing_id)
        ) {$this->charset};");

        dbDelta("CREATE TABLE {$tables['messages']} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            conversation_id BIGINT UNSIGNED NOT NULL,
            sender_id BIGINT UNSIGNED NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            content VARCHAR(4000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
            inquiry_id BIGINT UNSIGNED DEFAULT NULL,
            offer_id BIGINT UNSIGNED DEFAULT NULL,
            attachment_id BIGINT UNSIGNED DEFAULT NULL,
            PRIMARY KEY (id),
            FULLTEXT KEY ft_content (content),
            INDEX idx_conv (conversation_id),
            INDEX idx_created_at (created_at)
        ) {$this->charset};");

        dbDelta("CREATE TABLE {$tables['read_receipts']} (
            message_id BIGINT UNSIGNED NOT NULL,
            user_id BIGINT UNSIGNED NOT NULL,
            PRIMARY KEY (message_id, user_id),
            INDEX idx_user (user_id)
        ) {$this->charset};");

        // Add constraints safely after dbDelta
        $this->add_foreign_key_if_missing(
            $wpdb->prefix . 'um_conversation_participants',
            'fk_conv_participant_conv',
            "ALTER TABLE {$tables['conversation_participants']}
                ADD CONSTRAINT fk_conv_participant_conv
                FOREIGN KEY (conversation_id) REFERENCES {$tables['conversations']}(id) ON DELETE CASCADE"
        );
        $this->add_foreign_key_if_missing(
            $wpdb->prefix . 'um_messages',
            'fk_msg_conv',
            "ALTER TABLE {$tables['messages']}
                ADD CONSTRAINT fk_msg_conv
                FOREIGN KEY (conversation_id) REFERENCES {$tables['conversations']}(id) ON DELETE CASCADE"
        );
        $this->add_foreign_key_if_missing(
            $wpdb->prefix . 'um_messages',
            'fk_msg_sender',
            "ALTER TABLE {$tables['messages']}
                ADD CONSTRAINT fk_msg_sender
                FOREIGN KEY (sender_id) REFERENCES {$wpdb->prefix}users(ID)"
        );
        $this->add_foreign_key_if_missing(
            $wpdb->prefix . 'um_read_receipts',
            'fk_read_msg',
            "ALTER TABLE {$tables['read_receipts']}
                ADD CONSTRAINT fk_read_msg
                FOREIGN KEY (message_id) REFERENCES {$tables['messages']}(id) ON DELETE CASCADE"
        );
        $this->add_foreign_key_if_missing(
            $wpdb->prefix . 'um_read_receipts',
            'fk_read_user',
            "ALTER TABLE {$tables['read_receipts']}
                ADD CONSTRAINT fk_read_user
                FOREIGN KEY (user_id) REFERENCES {$wpdb->prefix}users(ID)"
        );

    }
    function add_foreign_key_if_missing( $table, $constraint_name, $sql ) {
        global $wpdb;

        $existing = $wpdb->get_var( $wpdb->prepare("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = %s
            AND CONSTRAINT_NAME = %s
        ", $table, $constraint_name ) );

        if ( ! $existing ) {
            $wpdb->query( $sql );
        }
    }


    function create_conversation($user_ids = [], $listing_ids = []) {
        global $wpdb;
        $tables = $this->tables;
        $participant_hash = $this->generate_participant_hash($user_ids, $listing_ids);

        // Check if this exact combination already exists
        $existing_id = $wpdb->get_var($wpdb->prepare("
            SELECT id FROM {$tables['conversations']}
            WHERE participant_hash = %s
            LIMIT 1
        ", $participant_hash));
        if ($existing_id === false) { return new WP_Error('db_insert_error', 'DB query error', [ 'status' => 500, ]); }
        if ($existing_id) { return $existing_id; }

        // Begin transaction
        $wpdb->query('START TRANSACTION');

        // Insert new conversation
        $now = current_time('mysql');
        $insert_convo_result = $wpdb->insert($tables['conversations'], [
            'participant_hash' => $participant_hash,
            'created_at'       => $now,
            'updated_at'       => $now
        ]);
        if ($insert_convo_result === false) {
            $wpdb->query('ROLLBACK');
            return new WP_Error('db_insert_error', 'DB write error.', [ 'status' => 500, ]);
        }
        $conversation_id = $wpdb->insert_id;

        // Insert participants
        foreach ($user_ids as $user_id) {
            $result = $wpdb->insert($tables['conversation_participants'], [
                'conversation_id' => $conversation_id,
                'user_id'         => $user_id
            ]);
            if ($result === false) {
                $wpdb->query('ROLLBACK');
                return new WP_Error('db_insert_error', 'DB write error', [ 'status' => 500, ]);
            }
        }
        foreach ($listing_ids as $listing_id) {
            $result = $wpdb->insert($tables['conversation_participants'], [
                'conversation_id' => $conversation_id,
                'listing_id'      => $listing_id
            ]);
            if ($result === false) {
                $wpdb->query('ROLLBACK');
                return new WP_Error('db_insert_error', 'DB write error', [ 'status' => 500, ]);
            }
        }

        $wpdb->query('COMMIT');
        return $conversation_id;
    }

    function generate_participant_hash($user_ids, $listing_ids) {
        // Normalize and sort participant IDs
        $user_ids = array_unique(array_map('intval', $user_ids));
        $listing_ids = array_unique(array_map('intval', $listing_ids));
        sort($user_ids);
        sort($listing_ids);

        // Create a consistent fingerprint string
        $user_part    = array_map(fn($id) => 'U' . $id, $user_ids);
        $listing_part = array_map(fn($id) => 'L' . $id, $listing_ids);
        $fingerprint  = implode('-', array_merge($user_part, $listing_part));
        return hash('sha256', $fingerprint);
    }

    // Sends a message in an existing conversation
    function send_message($conversation_id, $sender_id, $content, $inquiry_id = null, $offer_id = null, $attachment_id = null) {
        global $wpdb;
        $tables = $this->tables;
        $now = current_time('mysql');
        $current_user_id = get_current_user_id();

        // Access control: check user logged in
        if (!is_user_logged_in()) {
            return new WP_Error('unauthorized', 'You are not allowed to send messages if you are not logged in.', [ 'status' => 403]);
        }

        // Access control: allow only if the sender is the current user or user is an admin
        if ($current_user_id !== (int) $sender_id && !current_user_can('manage_options')) {
            return new WP_Error('unauthorized', 'You are not allowed to send messages as this user.', [ 'status' => 403 ]);
        }

        // Access control: Check logged in user is a participant in conversation_id
        $is_participant = $this->user_is_participant(get_current_user_id(), $conversation_id);
        if ( is_wp_error($is_participant) && !current_user_can('manage_options')) {
            return $is_participant;
        }

        // Begin transaction
        $wpdb->query('START TRANSACTION');

        $message = [
            'conversation_id' => $conversation_id,
            'sender_id'       => $sender_id,
            'content'         => $content,
            'inquiry_id'      => $inquiry_id,
            'offer_id'        => $offer_id,
            'attachment_id'   => $attachment_id,
            'created_at'      => $now,
            'updated_at'      => $now,
        ];
        $insert_result = $wpdb->insert($tables['messages'], $message);
        if ($insert_result === false) {
            $wpdb->query('ROLLBACK');
            return new WP_Error('db_insert_error', 'Failed to insert message.', [ 'status' => 500, ]);
        }
        $message['message_id'] = $wpdb->insert_id;

        // Set profile image
        $profile_image = get_profile_image($sender_id);
        $message['sender_profile_image_url'] = $profile_image['url'];

        // Attempt to update conversation's updated_at
        $update_result = $wpdb->update( $tables['conversations'], ['updated_at' => $now], ['id' => $conversation_id]);
        if ($update_result === false) {
            $wpdb->query('ROLLBACK');
            return new WP_Error('db_update_error', 'DB update error', [ 'status' => 500, ]);
        }

        // All DB operations succeeded
        $wpdb->query('COMMIT');
        return $message;
    }

    // Gets user conversations, ordered by most recent message
    function get_user_conversations($user_id, $limit = 20, $cursor_id = null, $inquiry_id = null, $get_newer = false) {
        global $wpdb;
        $tables              = $this->tables;
        $current_user_id     = get_current_user_id();
        $user_listing_ids    = [];
        $inquiry_listing_ids = [];

        // Access control: check user logged in
        if (!is_user_logged_in()) {
            return new WP_Error('unauthorized', 'You are not allowed to see messages if you are not logged in.', [ 'status' => 403 ]);
        }

        // Access control: allow only if the user_id is the current user or user is an admin
        if ($current_user_id !== (int) $user_id && !current_user_can('manage_options')) {
            return new WP_Error('unauthorized', 'You are not allowed to send messages as this user.', [ 'status' => 403 ]);
        }

        // Get inquiry listing IDs if inquiry id provided
        if ($inquiry_id) {
            $inquiry_listing_ids = get_post_meta($inquiry_id, 'listings_invited', true);
            if (!is_array($inquiry_listing_ids) or empty($inquiry_listing_ids)) {
                return [];
            }
        }

        // else Get listing IDs the user owns
        $user_listing_ids = get_user_meta($user_id, 'listings', true);
        if (!is_array($user_listing_ids)) {
            $user_listing_ids = [];
        }

        // Get cursor time if cursor provided
        $cursor_time = null;
        if ($cursor_id) {
            $cursor_time = $wpdb->get_var($wpdb->prepare("
                SELECT created_at FROM {$tables['messages']}
                WHERE id = %d
            ", $cursor_id));
            if ($cursor_time === false) { return new WP_Error('db_error', 'DB query error', [ 'status' => 500 ]); }
        }

        $query = get_conversations_query($tables, $user_id, $cursor_time, $user_listing_ids, $inquiry_listing_ids, $get_newer, $limit);
        $query_sql = $query['sql'];
        $query_params = $query['args'];
        $prepared_query = $wpdb->prepare($query_sql, ...$query_params);
        $conversations = $wpdb->get_results($prepared_query);
        if ($conversations === false) { return new WP_Error('db_error', 'DB query error', [ 'status' => 500 ]); }

        // Now add participant names
        foreach ($conversations as &$conv) {
            $conv->participants = $this->get_conversation_participant_names($conv->conversation_id, $user_id, true);
        }

        return $conversations;
    }

    function get_conversation_participant_names($conversation_id, $sender_id, $exclude_sender=false) {
        global $wpdb;
        $tables = $this->tables;
        $user_participants = [];
        $listing_participants = [];

        $participant_rows = $wpdb->get_results($wpdb->prepare("
            SELECT cp.user_id, cp.listing_id
            FROM {$tables['conversation_participants']} cp
            WHERE cp.conversation_id = %d
        ", $conversation_id));
        if ($participant_rows === false) { return new WP_Error('db_error', 'DB query error', [ 'status' => 500 ]); }

        foreach ($participant_rows as $row) {
            if ($row->user_id) {
                if ($exclude_sender and $row->user_id == $sender_id) { continue; }
                $name = $wpdb->get_var($wpdb->prepare("
                    SELECT display_name FROM {$wpdb->users}
                    WHERE ID = %d
                ", $row->user_id));
                if ($name === false) { return new WP_Error('db_error', 'DB query error', [ 'status' => 500 ]); }
                if ($name) {
                    $user_participants[] = clean_display_name($name);
                }

            } elseif ($row->listing_id) {
                $name = $wpdb->get_var($wpdb->prepare("
                    SELECT pm.meta_value
                    FROM {$wpdb->posts} p
                    LEFT JOIN {$wpdb->postmeta} pm ON pm.post_id = p.ID AND pm.meta_key = 'listing_name'
                    WHERE p.ID = %d
                    LIMIT 1
                ", $row->listing_id));
                if ($name === false) { return new WP_Error('db_error', 'DB query error', [ 'status' => 500 ]); }
                if (!$name) { $name = get_the_title($row->listing_id); } // Fallback to post title
                $listing_participants[] = $name;
            }
        }

        return array_merge($user_participants, $listing_participants);
    }


    // Gets messages for a conversation, latest first
    function get_conversation_messages($conversation_id, $limit = 20, $cursor_id = null, $get_newer = false) {
        global $wpdb;
        $tables = $this->tables;

        // Access control: check user logged in
        if (!is_user_logged_in()) {
            return new WP_Error('unauthorized', 'You are not allowed to see messages if you are not logged in.', [ 'status' => 403 ]);
        }

        // Access control: Check logged in user is a participant in conversation_id
        $is_participant = $this->user_is_participant(get_current_user_id(), $conversation_id);
        if ( is_wp_error($is_participant) && !current_user_can('manage_options')) {
            return $is_participant;
        }

        $user_id = get_current_user_id();
        $cursor_comparison = $get_newer ? '>' : '<';

        if ($cursor_id) {
            // Get timestamp of cursor message
            $cursor_time = $wpdb->get_var($wpdb->prepare("
                SELECT created_at FROM {$tables['messages']}
                WHERE id = %d
            ", $cursor_id));
            if ($cursor_time === false) { return new WP_Error('db_error', 'DB query error', [ 'status' => 500 ]); }

            $query = $wpdb->prepare("
                SELECT
                    m.*,
                    u.display_name AS sender_name,
                    rr.user_id IS NOT NULL AS is_read
                FROM {$tables['messages']} m
                LEFT JOIN {$wpdb->users} u ON m.sender_id = u.ID
                LEFT JOIN {$tables['read_receipts']} rr
                    ON rr.message_id = m.id AND rr.user_id = %d
                WHERE m.conversation_id = %d
                  AND m.created_at {$cursor_comparison} %s
                ORDER BY m.created_at DESC
                LIMIT %d
            ", $user_id, $conversation_id, $cursor_time, $limit);
        } else {
            $query = $wpdb->prepare("
                SELECT
                    m.*,
                    u.display_name AS sender_name,
                    rr.user_id IS NOT NULL AS is_read
                FROM {$tables['messages']} m
                LEFT JOIN {$wpdb->users} u ON m.sender_id = u.ID
                LEFT JOIN {$tables['read_receipts']} rr
                    ON rr.message_id = m.id AND rr.user_id = %d
                WHERE m.conversation_id = %d
                ORDER BY m.created_at DESC
                LIMIT %d
            ", $user_id, $conversation_id, $limit);
        }

        $result = $wpdb->get_results($query);
        if ($result === false) { return new WP_Error('db_error', 'DB query error', [ 'status' => 500 ]); }

        // Add sender profile image url
        $profile_images = [];
        foreach ($result as &$item) {
            $sender_id = $item->sender_id ?? null;
            if ($sender_id) {
                // Check if we already fetched this sender's profile image
                if (!isset($profile_images[$sender_id])) {
                    $profile_image = get_profile_image($sender_id);
                    $profile_images[$sender_id] = $profile_image['url'] ?? null;
                }

                // Assign the profile image URL to the result item
                $item->sender_profile_image_url = $profile_images[$sender_id];
            } else {
                $item->sender_profile_image_url = null;
            }
        }
        unset($item); // Best practice when modifying array items by reference


        return $result;
    }

    function user_is_participant($user_id, $conversation_id) {
        global $wpdb;
        $tables = $this->tables;

        // Check if the sender is a user participant
        $is_user_participant = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$tables['conversation_participants']}
             WHERE conversation_id = %d AND user_id = %d",
            $conversation_id,
            $user_id
        ));
        if ($is_user_participant === false) { return new WP_Error('db_error', 'DB query error', [ 'status' => 500 ]); }

        // Check if any of the sender's listings are participants
        $listing_ids = get_user_meta($user_id, 'listings', true);
        if (!is_array($listing_ids)) { $listing_ids = []; }
        $is_listing_participant = 0;
        if (!empty($listing_ids)) {
            // Prepare placeholders for IN clause
            $placeholders = implode(',', array_fill(0, count($listing_ids), '%d'));
            $sql = "
                SELECT COUNT(*) FROM {$tables['conversation_participants']}
                WHERE conversation_id = %d
                AND listing_id IN ($placeholders)
            ";
            $values = array_merge([$conversation_id], $listing_ids);
            $is_listing_participant = $wpdb->get_var($wpdb->prepare($sql, ...$values));
            if ($is_listing_participant === false) { return new WP_Error('db_error', 'DB query error', [ 'status' => 500 ]); }
        }

        // Final access check
        if (!$is_user_participant && !$is_listing_participant) {
            return new WP_Error('forbidden', 'You are not a participant in this conversation.', [ 'status' => 403 ]);
        }
        return true;
    }


    function add_read_receipt($message_id, $user_id) {
        global $wpdb;
        $tables = $this->tables;

        // Use $wpdb->replace to avoid duplicates (primary key on message_id + user_id)
        $result = $wpdb->replace(
            $tables['read_receipts'],
            [
                'message_id' => intval($message_id),
                'user_id'    => intval($user_id),
            ],
            [
                '%d', // message_id format
                '%d', // user_id format
            ]
        );

        if ($result === false) {
            return new WP_Error('db_error', 'DB write error', [ 'status' => 500 ]);
        }
        return true;
    }

    function remove_read_receipt($message_id, $user_id) {
        global $wpdb;
        $tables = $this->tables;

        $result = $wpdb->delete(
            $tables['read_receipts'],
            [
                'message_id' => intval($message_id),
                'user_id'    => intval($user_id),
            ],
            [
                '%d', // message_id format
                '%d', // user_id format
            ]
        );

        if ($result === false) {
            return new WP_Error('db_error', 'DB write error', [ 'status' => 500 ]);
        }
        return true;
    }

}

$user_messages_plugin = new UserMessagesPlugin();
