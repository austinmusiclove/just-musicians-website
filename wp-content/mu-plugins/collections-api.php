<?php
function create_collection() {
}
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'collections', [
        'methods' => 'POST',
        'callback' => 'create_collection',
    ]);
});

function delete_collection() {
}
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'collections', [
        'methods' => 'DELETE',
        'callback' => 'delete_collection',
    ]);
});

function remove_listing_from_collection() {
}
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'collections', [
        'methods' => 'PUT',
        'callback' => 'remove_listing_from_collection',
    ]);
});

function add_listing_to_collection() {
}
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'collections', [
        'methods' => 'PUT',
        'callback' => 'add_listing_to_collection',
    ]);
});

function get_collections() {
    $result = array();
    $user_id = $_GET['user_id'];
    $args = array(
        'post_type' => 'collection',
        'nopaging' => true,
        'meta_query' => array(
            array(
                'key' => 'user',
                'value' => $user_id,
            )
        ),
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while( $query->have_posts() ) {
            $query->the_post();
            array_push($result, array(
                'ID' => get_the_ID(),
                'name' => get_field('name'),
                'user' => get_field('user'),
                'listings' => get_field('listings'),
            ));
        }
    }
    $favorites = get_user_meta($user_id, 'favorites', false);
    array_push($result, array(
        'name' => 'Favorites',
        'listings' => $favorites
    ));
    return $result;
}
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'collections', [
        'methods' => 'GET',
        'callback' => 'get_collections',
    ]);
});
