<?php

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

// View/edit the user's Stripe customer ID on the profile page
add_action('show_user_profile', 'add_stripe_customer_id_field');
add_action('edit_user_profile', 'add_stripe_customer_id_field');

function add_stripe_customer_id_field($user) {
    if (!current_user_can('manage_options')) return;
    $value = get_user_meta($user->ID, 'stripe_customer_id', true);
    ?>
    <table class="form-table" role="presentation">
        <tr>
            <th><label for="stripe_customer_id">Stripe Customer ID</label></th>
            <td>
                <?php wp_nonce_field('stripe_customer_id_nonce', 'stripe_customer_id_nonce'); ?>
                <input type="text" name="stripe_customer_id" id="stripe_customer_id" value="<?php echo esc_attr($value); ?>" class="regular-text" placeholder="customer_id">
                <p class="description">The linked Stripe Customer. Leave empty to fall back to email-based checkout.</p>
            </td>
        </tr>
    </table>
    <?php
}

add_action('personal_options_update', 'save_stripe_customer_id_field');
add_action('edit_user_profile_update', 'save_stripe_customer_id_field');

function save_stripe_customer_id_field($user_id) {
    if (!current_user_can('manage_options')) return;
    if (!isset($_POST['stripe_customer_id_nonce']) || !wp_verify_nonce($_POST['stripe_customer_id_nonce'], 'stripe_customer_id_nonce')) return;

    $customer_id = isset($_POST['stripe_customer_id']) ? sanitize_text_field(wp_unslash($_POST['stripe_customer_id'])) : '';
    if ($customer_id === '') {
        delete_user_meta($user_id, 'stripe_customer_id');
    } else {
        update_user_meta($user_id, 'stripe_customer_id', $customer_id);
    }
}

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

// Show the user's access table on the profile page
add_action('show_user_profile', 'add_user_access_table');
add_action('edit_user_profile', 'add_user_access_table');

function add_user_access_table($user) {
    if (!current_user_can('manage_options')) return;

    global $wpdb;
    $table = hm_get_access_table();
    $access_rows = $wpdb->get_results($wpdb->prepare(
        "SELECT subject_type, subject_id, access_type FROM {$table} WHERE user_id = %d ORDER BY subject_id ASC",
        $user->ID
    ));
    foreach ($access_rows as $row) {
        $post = get_post($row->subject_id);
        $row->subject_title = $post ? $post->post_title : '';
    }
    ?>
    <table class="form-table" role="presentation">
        <tr>
            <th><label>Access</label></th>
            <td>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th>Subject Type</th>
                            <th>Subject ID</th>
                            <th style="width: 40%">Subject</th>
                            <th>Access Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($access_rows) { ?>
                            <?php foreach ($access_rows as $row) { ?>
                                <tr>
                                    <td><?php echo esc_html($row->subject_type); ?></td>
                                    <td><?php echo esc_html($row->subject_id); ?></td>
                                    <td><?php echo esc_html($row->subject_title); ?></td>
                                    <td><?php echo esc_html($row->access_type); ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4">No access granted.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <p class="description">Access levels granted to this user.</p>
            </td>
        </tr>
    </table>
    <?php
}
