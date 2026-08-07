<?php

function get_seo_page_title( $category_slug, $location_label ) {
    return get_seo_category_plural_name( $category_slug ) . ' in ' . $location_label;
}

function seo_pages_title_tags( $title_parts ) {
    // Check if we are on one of the custom rewrite pages
    $category = get_query_var('seo-category') ?? 0;
    $location = get_query_var('seo-location') ?? 0;
    if ( $category && $location ) {

        $location_data = get_seo_location($location);
        if ( $location_data ) {
            $title_parts['title'] = get_seo_page_title( $category, $location_data['city'] . ', ' . $location_data['state'] );
        }

    }

    return $title_parts;
}
add_filter( 'document_title_parts', 'seo_pages_title_tags' );
