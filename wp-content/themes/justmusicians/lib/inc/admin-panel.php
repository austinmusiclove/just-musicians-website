<?php


// Disable admin bar for all users
show_admin_bar(false);

// Restrict non-admin, non-editor users from wp-admin
add_action('admin_init', function () {
    if (!is_user_logged_in()) {
        return;
    }

    // Always allow AJAX requests used by the front-end
    if (wp_doing_ajax()) {
        return;
    }

    // Allow admins and editors
    if (current_user_can('manage_options') || current_user_can('edit_others_posts')) {
        return;
    }

    wp_safe_redirect(home_url());
    exit;
});

// Add author id to author column
add_filter( 'the_author', function( $display_name ) {
    global $post;

    if ( ! is_admin() || ! $post ) {
        return $display_name;
    }

    $user_id = $post->post_author;

    return "{$user_id} :: {$display_name}";
});
