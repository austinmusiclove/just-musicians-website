<?php

function seo_pages_title_tags( $title_parts ) {
    // Check if we are on one of the custom rewrite pages
    $category = get_query_var('seo-category') ?? 0;
    $location = get_query_var('seo-location') ?? 0;
    if ( $category && $location ) {

        $term = get_term_by('slug', $category, 'mcategory');
        if ( $term and !is_wp_error( $term ) ) {
            $title_parts['title'] = 'Top ' . $term->name . 's in Austin, Texas';
        }

    }

    return $title_parts;
}
add_filter( 'document_title_parts', 'seo_pages_title_tags' );
