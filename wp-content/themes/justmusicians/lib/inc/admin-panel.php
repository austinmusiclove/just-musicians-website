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


// Add listing count to users table
add_filter('manage_users_columns', 'add_listings_count_column');
function add_listings_count_column($columns) {
    $columns['listings_count'] = 'Listings';
    return $columns;
}

add_action('manage_users_custom_column', 'show_listings_count_column', 10, 3);
function show_listings_count_column($value, $column_name, $user_id) {
    if ($column_name === 'listings_count') {
        // Get ACF user meta field named 'listings' (array of post IDs)
        $listings = get_field('listings', 'user_' . $user_id);

        if (!empty($listings) && is_array($listings)) {
            return count($listings);
        }

        return 0;
    }

    return $value;
}
