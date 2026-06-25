<?php
if (!defined('ABSPATH')) { exit; }

function update_proposal($args) {
    $proposal_id = (int) ($args['ID'] ?? 0);
    if (!$proposal_id) {
        return new WP_Error('missing_proposal_id', 'Proposal ID is required.', ['status' => 400]);
    }

    if (empty($args['meta_input'])) {
        return new WP_Error('nothing_to_update', 'No fields to update.', ['status' => 400]);
    }

    $proposal = get_post($proposal_id);
    if (!$proposal || $proposal->post_type !== 'proposal') {
        return new WP_Error('not_found', 'Proposal not found.', ['status' => 404]);
    }

    $result = wp_update_post($args, true);

    return $result;
}

function respond_to_inquiry_proposal($args) {
    $proposal_id = (int) ($args['ID'] ?? 0);

    $authorized = user_can_update_proposal($proposal_id);
    if (is_wp_error($authorized)) {
        return $authorized;
    }

    $initial_status = get_post_meta($proposal_id, 'status', true);

    // If availability is not set or not in approved options, default it to unavailable
    if (!isset($args['meta_input'])) { $args['meta_input'] = []; }
    if (!array_key_exists('availability', $args['meta_input'])) {
        $args['meta_input']['availability'] = 'unavailable';
    } elseif (!in_array($args['meta_input']['availability'], ['available', 'unavailable'], true)) {
        $args['meta_input']['availability'] = 'unavailable';
    }

    // Set status to the availability
    $args['meta_input']['status'] = $args['meta_input']['availability'];

    $result = update_proposal($args);
    if (is_wp_error($result)) {
        return $result;
    }

    $event_id    = (int) get_post_meta($proposal_id, 'event', true);
    $event       = get_post($event_id);
    $author_id   = $event->post_author;
    if ($initial_status === 'inquiry') {
        send_inquiry_proposal_response_email($author_id, $proposal_id, $event_id);
        add_inquiry_response_notification($author_id, $proposal_id);
    } else if ($initial_status === 'stale') {
        clear_notifications(HM_NOTIFICATION_TYPE_EVENT_DT_CHANGE, $proposal_id);
        add_inquiry_response_notification($author_id, $proposal_id);
    } else {
        add_inquiry_response_update_notification($author_id, $proposal_id);
    }

    return $result;
}
