<?php
function get_artist_post_id() {
    $artist_id = $_GET['artist_id'];

    $args = array(
        'post_type' => 'artist',
        'posts_per_page' => 1,
        'meta_key' => 'artist_id',
        'meta_value' => $artist_id,
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $query->the_post();
        return get_the_ID();
    }
    return 0;
}
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'artists/post_id', [
        'methods' => 'GET',
        'callback' => 'get_artist_post_id',
    ]);
});
