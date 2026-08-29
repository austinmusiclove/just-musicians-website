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

        if (!empty($listings) and is_array($listings)) {
            // Get only valid listings (still existing + published)
            $valid_listings = array_filter($listings, function ($post_id) {
                $post = get_post($post_id);
                return $post and $post->post_status === 'publish';
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

// Add favorites and collections columns to the users table
add_filter('manage_users_columns', 'add_user_columns');
add_action('manage_users_custom_column', 'show_user_columns', 10, 3);
function add_user_columns($columns) {
    $columns['favorites_count'] = 'Favorites';
    $columns['email_verified'] = 'Email Verified';
    $columns['collections_count'] = 'Collections';
    return $columns;
}
function show_user_columns($value, $column_name, $user_id) {
    if ($column_name === 'email_verified') {
        // Get ACF user meta field for 'email_verified' (boolean)
        $verified = get_user_meta($user_id, 'email_verified', true);
        return $verified ? '1' : '0';
    }

    if ($column_name === 'favorites_count') {
        // Get ACF user meta field for 'favorites' (array of post IDs)
        $favorites = get_user_meta($user_id, 'favorites', true);

        if (!empty($favorites) && is_array($favorites)) {
            // Filter to count only valid favorite posts (published posts)
            $valid_favorites = array_filter($favorites, function ($post_id) {
                $post = get_post($post_id);
                return $post && $post->post_status === 'publish';
            });
            return count($valid_favorites);
        }
        return 0;
    }

    if ($column_name === 'collections_count') {
        // Get ACF user meta field for 'collections' (array of post IDs)
        $collections = get_user_meta($user_id, 'collections', true);

        if (!empty($collections) && is_array($collections)) {
            // Filter to count only valid collection posts (published posts)
            $valid_collections = array_filter($collections, function ($post_id) {
                $post = get_post($post_id);
                return $post && $post->post_status === 'publish';
            });
            return count($valid_collections);
        }
        return 0;
    }

    return $value;
}

// Add author id to author column
add_filter( 'the_author', function( $display_name ) {
    global $post;

    if ( ! is_admin() || ! $post ) {
        return $display_name;
    }

    $user_id = $post->post_author;

    return "{$user_id} :: {$display_name}";
});


// Add Pro Talent Buyer capability toggles to user profile
add_action('show_user_profile', 'add_hm_buyer_pro_field');
add_action('edit_user_profile', 'add_hm_buyer_pro_field');

function add_hm_buyer_pro_field($user) {
    if (!current_user_can('manage_options')) return;
    ?>
    <table class="form-table" role="presentation">
        <tr>
            <th><label>Pro Talent Buyer</label></th>
            <td>
                <?php wp_nonce_field('hm_buyer_pro_nonce', 'hm_buyer_pro_nonce'); ?>
                <fieldset>
                    <label for="hm_buyer_pro">
                        <input type="checkbox" name="hm_buyer_pro" id="hm_buyer_pro" value="1"
                            <?php checked($user->has_cap('hm_buyer_pro')); ?>>
                        Pro Talent Buyer
                    </label>
                    <br>
                    <label for="hm_buyer_pro_lifetime">
                        <input type="checkbox" name="hm_buyer_pro_lifetime" id="hm_buyer_pro_lifetime" value="1"
                            <?php checked($user->has_cap('hm_buyer_pro_lifetime')); ?>>
                        Pro Talent Buyer (Lifetime)
                    </label>
                </fieldset>
            </td>
        </tr>
    </table>
    <?php
}

// Save Pro Talent Buyer capabilities
add_action('personal_options_update', 'save_hm_buyer_pro_field');
add_action('edit_user_profile_update', 'save_hm_buyer_pro_field');

function save_hm_buyer_pro_field($user_id) {
    if (!current_user_can('manage_options')) return;
    if (!isset($_POST['hm_buyer_pro_nonce']) || !wp_verify_nonce($_POST['hm_buyer_pro_nonce'], 'hm_buyer_pro_nonce')) return;

    $user = get_userdata($user_id);
    if (!$user) return;

    if (isset($_POST['hm_buyer_pro'])) {
        $user->add_cap('hm_buyer_pro');
    } else {
        $user->remove_cap('hm_buyer_pro');
    }

    if (isset($_POST['hm_buyer_pro_lifetime'])) {
        $user->add_cap('hm_buyer_pro_lifetime');
    } else {
        $user->remove_cap('hm_buyer_pro_lifetime');
    }
}
