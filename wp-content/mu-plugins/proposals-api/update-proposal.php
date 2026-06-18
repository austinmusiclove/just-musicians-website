<?php
if (!defined('ABSPATH')) { exit; }

function update_proposal($args) {
    $proposal_id = (int) ($args['proposal_id'] ?? 0);
    if (!$proposal_id) {
        return new WP_Error('missing_proposal_id', 'Proposal ID is required.', ['status' => 400]);
    }

    $proposal = get_post($proposal_id);
    if (!$proposal || $proposal->post_type !== 'proposal') {
        return new WP_Error('not_found', 'Proposal not found.', ['status' => 404]);
    }

    $meta_input = [];
    foreach (['status', 'availability', 'draw', 'quote', 'details'] as $field) {
        if (array_key_exists($field, $args)) {
            $meta_input[$field] = $args[$field];
        }
    }

    if (empty($meta_input)) {
        return new WP_Error('nothing_to_update', 'No fields to update.', ['status' => 400]);
    }

    $result = wp_update_post([
        'ID'         => $proposal_id,
        'meta_input' => $meta_input,
    ], true);

    return $result;
}

function respond_to_inquiry_proposal($args) {
    $proposal_id = (int) ($args['proposal_id'] ?? 0);

    $authorized = user_can_update_proposal($proposal_id);
    if (is_wp_error($authorized)) {
        return $authorized;
    }

    $initial_status = get_post_meta($proposal_id, 'status', true);

    $args['status'] = 'applied';

    $result = update_proposal($args);
    if (is_wp_error($result)) {
        return $result;
    }

    if ($initial_status === 'inquiry') {
        $event_id    = (int) get_post_meta($proposal_id, 'event', true);
        $event       = get_post($event_id);
        $author_id   = $event->post_author;
        send_inquiry_proposal_response_email($author_id, $proposal_id, $event_id);
        add_inquiry_response_notification($author_id, $proposal_id);
    }

    return $result;
}
