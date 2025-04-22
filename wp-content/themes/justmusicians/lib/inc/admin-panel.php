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
            // Get only valid listings (still existing + published)
            $valid_listings = array_filter($listings, function ($post_id) {
                $post = get_post($post_id);
                return $post && $post->post_status === 'publish';
            });
            return count($listings);
        }
        return 0;
    }
    return $value;
}


// Sort users by date registered
add_filter('manage_users_columns', function($columns) {
    $columns['registered'] = 'Registered';
    return $columns;
});
add_filter('manage_users_custom_column', function($output, $column_name, $user_id) {
    if ($column_name === 'registered') {
        $user = get_userdata($user_id);
        return date('Y-m-d H:i', strtotime($user->user_registered));
    }
    return $output;
}, 10, 3);
add_filter('manage_users_sortable_columns', function($columns) {
    $columns['registered'] = 'user_registered';
    return $columns;
});
add_action('pre_get_users', function($query) {
    if (!is_admin()) return;

    if ($query->get('orderby') === 'user_registered') {
        $query->set('orderby', 'user_registered');
    }
});

