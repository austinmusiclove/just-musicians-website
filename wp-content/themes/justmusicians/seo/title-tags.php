<?php

function get_seo_page_title( $category_name, $location_label ) {
    return $category_name . 's in ' . $location_label;
}

function seo_pages_title_tags( $title_parts ) {
    // Check if we are on one of the custom rewrite pages
    $category = get_query_var('seo-category') ?? 0;
    $location = get_query_var('seo-location') ?? 0;
    if ( $category && $location ) {

        $term = get_term_by('slug', $category, 'mcategory');
        if ( $term and !is_wp_error( $term ) ) {
            $location_data = get_seo_location($location);
            if ( $location_data ) {
                $title_parts['title'] = get_seo_page_title( $term->name, $location_data['city'] . ', ' . $location_data['state'] );
            }
        }

    }

    return $title_parts;
}
add_filter( 'document_title_parts', 'seo_pages_title_tags' );
