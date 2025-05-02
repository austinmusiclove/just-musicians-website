<?php
function create_user_collection($collection_name, $listing_id = null) {
    $user_id = get_current_user_id();
    $collection_name = trim(sanitize_text_field($collection_name));

    // Validate collection name
    if (empty($collection_name)) {
        return new WP_Error('empty_name', 'Collection name cannot be empty.');
    }

    // Disallow double quotes
    if (strpos($collection_name, '"') !== false) {
        return new WP_Error('invalid_character', 'Collection name cannot contain double quotes.');
    }

    // Get user's existing collection IDs
    $user_collections = get_user_meta($user_id, 'collections', true);
    if (!is_array($user_collections)) {
        $user_collections = [];
    }

    // Check if user already has a collection with the same name
    foreach ($user_collections as $collection_id) {
        if (get_post_meta($collection_id, 'name', true) === $collection_name) {
            return new WP_Error('duplicate_name', 'You already have a collection with that name.');
        }
    }

    // Create the collection post
    $args = [
        'post_type'   => 'collection',
        'post_status' => 'publish',
        'post_title'  => wp_strip_all_tags($collection_name),
        'meta_input'  => [ 'name' => $collection_name ],
    ];
    // Add listing to the collection if provided
    $listings = null;
    if ($listing_id && get_post_type($listing_id) === 'listing' && get_post_status($listing_id) === 'publish') {
        $args['meta_input']['listings'] = [$listing_id];
        $listings = [$listing_id];
    }
    $collection_id = wp_insert_post($args);

    // Error check post creation
    if (is_wp_error($collection_id) || !$collection_id) {
        return new WP_Error('creation_failed', 'Failed to create collection.');
    }

    // Add new collection ID to user's collections meta
    $user_collections[] = $collection_id;
    update_user_meta($user_id, 'collections', $user_collections);

    return [
        'post_id'  => $collection_id,
        'name'     => $collection_name,
        'listings' => $listings,
    ];
}
