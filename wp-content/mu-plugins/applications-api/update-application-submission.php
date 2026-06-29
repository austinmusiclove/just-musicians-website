<?php
if (!defined('ABSPATH')) { exit; }

function update_application_submission($args) {
    $submission_id = (int) ($args['ID'] ?? 0);
    if (!$submission_id) {
        return new WP_Error('missing_submission_id', 'Submission ID is required.', ['status' => 400]);
    }

    if (empty($args['meta_input'])) {
        return new WP_Error('nothing_to_update', 'No fields to update.', ['status' => 400]);
    }

    $submission = get_post($submission_id);
    if (!$submission || $submission->post_type !== 'app_submission') {
        return new WP_Error('not_found', 'Submission not found.', ['status' => 404]);
    }

    $result = wp_update_post($args, true);

    return $result;
}

function update_user_submission_message($args) {
    $submission_id = (int) ($args['ID'] ?? 0);

    $authorized = user_can_update_application_submission($submission_id);
    if (is_wp_error($authorized)) {
        return $authorized;
    }

    if (!isset($args['meta_input'])) { $args['meta_input'] = []; }

    $result = update_application_submission($args);
    if (is_wp_error($result)) {
        return $result;
    }

    return $result;
}
