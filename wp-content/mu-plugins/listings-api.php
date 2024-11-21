<?php
function get_listings() {
    $results = array();
    $args = array(
        'post_type' => 'listing',
        'nopaging' => true,
        'tax_query' => array(
            array(
                'taxonomy' => 'genre',
                'field' => 'slug',
                'terms' => array('blues'),
            )
        )
    );
    $query = new WP_Query($args);
    while ($query->have_posts()) {
        $query->the_post();
        array_push($results, array(
            'title' => get_the_title(),
            'genre' => get_the_terms(get_the_ID(), 'genre'),
        ));
    }

    return $results;
}
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'listings', [
        'methods' => 'GET',
        'callback' => 'get_listings',
    ]);
});

