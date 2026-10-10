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
    unset( $post_types['inquiry'] ); // Deprecated
    unset( $post_types['event'] );
    unset( $post_types['proposal'] );
    unset( $post_types['application'] );
    unset( $post_types['app_submission'] );
    unset( $post_types['offer'] );
    unset( $post_types['youtubevideo'] );
    unset( $post_types['artist'] );
    unset( $post_types['performance'] );
    unset( $post_types['listing_review'] );
    unset( $post_types['buyer_review'] );
    unset( $post_types['venue_review'] );
    unset( $post_types['comp_report'] );
    unset( $post_types['review_submission'] ); // Keep this old post type unless all review submission posts are deleted
    unset( $post_types['tmp_code'] );
    unset( $post_types['glossary'] );
    unset( $post_types['glossary-term'] );
    unset( $post_types['venue'] );
    unset( $post_types['podcast'] );

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
    unset( $taxonomies['ensemble_size'] );
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

add_action( 'init', function() {
    wp_register_sitemap_provider( 'localecategories', new LC_Landing_Sitemap_Provider() );
} );

class LC_Landing_Sitemap_Provider extends WP_Sitemaps_Provider {
    public function __construct() {
        $this->name = 'localecategories';
        $this->object_type = 'localecategories';
    }

    public function get_url_list( $page_num, $object_subtype = '' ) {
        if ( 1 !== (int) $page_num ) { return []; }

        $posts = get_posts( [
            'post_type'      => 'lc-landing',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'ID',
            'order'          => 'ASC',
        ] );

        $urls = [];
        $seen = [];

        foreach ( $posts as $post ) {
            $url_path = get_post_meta( $post->ID, 'url_path', true );
            if ( empty( $url_path ) ) { continue; }

            $url = home_url( $url_path );
            if ( isset( $seen[ $url ] ) ) { continue; }
            $seen[ $url ] = true;

            $urls[] = [
                'loc'        => $url,
                'lastmod'    => get_post_modified_time( DATE_W3C, true, $post ),
                'changefreq' => 'weekly',
                'priority'   => 0.8,
            ];
        }

        return $urls;
    }

    public function get_max_num_pages( $object_subtype = '' ) {
        return empty( $this->get_url_list( 1, $object_subtype ) ) ? 0 : 1;
    }
}
