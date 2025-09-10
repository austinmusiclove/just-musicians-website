<?php
function custom_meta_descriptions() {
    // Check if we are on one of the custom rewrite pages
    $category = get_query_var('seo-category') ?? 0;
    $location = get_query_var('seo-location') ?? 0;
    if ( $category && $location ) {

        $term = get_term_by('slug', $category, 'mcategory');
        if ( $term and !is_wp_error( $term ) ) {
            $description = 'Discover ' . $term->name . 's in Austin, Texas. Find local artists to hire for your next live music event.';
            echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
        }

    }
}
add_action( 'wp_head', 'custom_meta_descriptions' );
