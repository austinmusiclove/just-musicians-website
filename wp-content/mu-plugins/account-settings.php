<?php

/**
 * Plugin Name: Hire More Musicians Account settings
 * Description: A custom plugin to manage account settings
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function get_account_settings() {
    $user_id = get_current_user_id();
    if (!$user_id) { return null; }

    $user_info = get_userdata($user_id);

    // Attempt to get profile_image and fall back to gravatar
    $profile_image = get_profile_image($user_id);

    return [
        'display_name'  => clean_display_name($user_info->display_name),
        'profile_image' => $profile_image,
    ];
}

function get_profile_image($user_id) {
    $profile_image = [
        'url'           => '',
        'filename'      => '',
        'attachment_id' => '',
        'worker'        => null,
    ];
    $avatar = get_field('avatar', 'user_' . $user_id);

    // Profile image exists
    if ($avatar && is_array($avatar) && !empty($avatar['url'])) {
        $profile_image['url']           = esc_url($avatar['url']);
        $profile_image['filename']      = $avatar['filename'];
        $profile_image['attachment_id'] = $avatar['ID'];

    // Fallback to Gravatar
    } else {
        $profile_image['url'] = get_avatar_url($user_id);
    }

    return $profile_image;
}

function update_account_settings($args) {
    $result = [];

    $user_id = get_current_user_id();
    if (!$user_id) { return $result; }

    // Update display name
    if (!empty($args['display_name'])) {
        wp_update_user([
            'ID'           => $user_id,
            'display_name' => $args['display_name']
        ]);
        $result['display_name'] = $args['display_name'];
    }

    // Handle profile image upload
    if (!empty($args['profile_image']) and
        !empty($args['profile_image']['file']) and
        !empty($args['profile_image']['filename']) and
        empty($args['profile_image']['attachment_id'])
    ) {
        $attachment_id = upload_attachment($args['profile_image']['file'], $args['profile_image']['filename'], null, 'profile image', null);
        if (is_wp_error($attachment_id)) { return $attachment_id; }

        // Save the image to the ACF 'avatar' field
        update_field('avatar', $attachment_id, 'user_' . $user_id);
        $result['profile_image_attachment_id'] = $attachment_id;
    }

    return $result;
}


function get_post_account_settings_args() {
    $sanitized_args = [];

    if (isset($_POST['display_name']))       { $sanitized_args['display_name']  = sanitize_text_field($_POST['display_name']); }
    if (isset($_POST['profile_image_meta'])) { $sanitized_args['profile_image'] = custom_parse_file($_POST['profile_image_meta'], 'profile_image'); }

    return $sanitized_args;
}
