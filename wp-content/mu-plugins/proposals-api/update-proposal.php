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

    $authorized = user_can_update_proposal($proposal_id);
    if (is_wp_error($authorized)) {
        return $authorized;
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

    return is_wp_error($result) ? $result : $result;
}
