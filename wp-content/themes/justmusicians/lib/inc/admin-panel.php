<?php


// Disable admin bar for all users
show_admin_bar(false);



// Add post id to admin panel listing post table
function add_post_id_column($columns) {
    $columns['post_id'] = 'ID';
    return $columns;
}
function show_post_id_column($column, $post_id) {
    if ($column === 'post_id') {
        echo $post_id;
    }
}
add_filter('manage_listing_posts_columns', 'add_post_id_column');
add_action('manage_listing_posts_custom_column', 'show_post_id_column', 10, 2);

