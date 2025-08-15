<?php

// Remove users from sitemap
add_filter( 'wp_sitemaps_add_provider', function( $provider, $name ) {
    if ( $name === 'users' ) { return false; }
    return $provider;
}, 10, 2 );

// Remove certain post types pages from sitemap
function remove_custom_post_types_from_sitemap( $post_types ) {
    // Post types to remove from sitemap
    unset( $post_types['collection'] );
    unset( $post_types['inquiry'] );
    unset( $post_types['youtubevideo'] );
    unset( $post_types['artist'] );
    unset( $post_types['performance'] );
    unset( $post_types['venue'] );
    unset( $post_types['venue_review'] );
    unset( $post_types['review_submission'] );

    return $post_types;
}
add_filter( 'wp_sitemaps_post_types', 'remove_custom_post_types_from_sitemap' );

// Remove taxonomies pages from sitemap
function remove_taxonomies_from_sitemap( $taxonomies ) {
    unset( $taxonomies['category'] );
    unset( $taxonomies['mcategory'] );
    unset( $taxonomies['genre'] );
    unset( $taxonomies['subgenre'] );
    unset( $taxonomies['instrumentation'] );
    unset( $taxonomies['setting'] );
    unset( $taxonomies['keyword'] );
    unset( $taxonomies['mediatag'] );

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
            'inquiries',
            'messages',
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

// Top [category] in [location] pages
add_action( 'init', function() {
    wp_register_sitemap_provider( 'featuredlistings', new Featured_Listing_Sitemap_Provider() );
} );
class Featured_Listing_Sitemap_Provider extends WP_Sitemaps_Provider {
    public function __construct() {
        $this->name = 'featuredlistings';
        $this->object_type = 'featuredlistings';
    }

    public function get_url_list( $page_num, $post_type = '' ) {
        $urls = [];

        $categories = [ 'country-bands', 'rock-bands', 'cover-bands' ];
        $locations  = [ 'austin-tx' ];

        foreach ( $categories as $category ) {
            foreach ( $locations as $location ) {
                $url = home_url( "/top/{$category}/{$location}" );
                $urls[] = [
                    'loc' => $url,
                    'lastmod' => current_time( 'Y-m-d\TH:i:sP' ),
                    'changefreq' => 'weekly',
                    'priority' => 0.8,
                ];
            }
        }

        return $urls;
    }

    public function get_max_num_pages($object_subtype = '') {
        return 1;
    }

}
