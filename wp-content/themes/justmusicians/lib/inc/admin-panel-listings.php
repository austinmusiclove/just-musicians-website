<?php

// Add listing admin panel columns
add_filter('manage_listing_posts_columns', 'add_listing_columns');
add_action('manage_listing_posts_custom_column', 'show_listing_columns', 10, 2);
add_filter('manage_edit-listing_sortable_columns', 'make_last_modified_column_sortable');
function add_listing_columns($columns) {
    $columns['post_id']       = 'ID';
    $columns['last_modified'] = 'Last Modified';
    return $columns;
}
function show_listing_columns($column, $post_id) {
    if ($column === 'post_id') {
        echo $post_id;
    }
    if ($column === 'last_modified') {
        // Get the post's last modified date
        $last_modified = get_post_modified_time('Y-m-d H:i', false, $post_id);

        // Display the last modified date
        echo $last_modified;
    }
}
function make_last_modified_column_sortable($columns) {
    $columns['last_modified'] = 'post_modified';
    return $columns;
}

