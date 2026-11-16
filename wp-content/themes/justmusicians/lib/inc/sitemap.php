<?php

// Remove users from sitemap
add_filter( 'wp_sitemaps_add_provider', function( $provider, $name ) {
    if ( $name === 'users' ) { return false; }
    return $provider;
}, 10, 2 );

// Remove taxonomies pages from sitemap
function remove_taxonomies_from_sitemap( $taxonomies ) {
    unset( $taxonomies['category'] );
    return $taxonomies;
}
add_filter( 'wp_sitemaps_taxonomies', 'remove_taxonomies_from_sitemap' );

// Remove pages from sitemap
function exclude_pages_by_slug_from_sitemap( $args, $post_type ) {
    if ( 'page' === $post_type ) {
        $slugs_to_exclude = [
            'email-verification',
            'account',
            'listings',
            'listing-form',
            'collections',
            'inquiries', // Deprecated
            'messages',
            'my-events',
            'my-gigs',
            'event-form',
            'applications',
            'submitted-applications',
            'application-form',
            'subscriptions',
            'checkout-success',
            'password-reset',
            'request-password-reset',
            'podcast',
        ];
        $page_ids = [];

        foreach ( $slugs_to_exclude as $slug ) {
            $page = get_page_by_path( $slug );
            if ( $page ) { $page_ids[] = $page->ID; }
        }

        if ( ! empty( $page_ids ) ) {
            $args['post__not_in'] = $page_ids;
        }
    }

    return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'exclude_pages_by_slug_from_sitemap', 10, 2 );
