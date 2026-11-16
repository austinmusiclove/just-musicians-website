<?php

function disable_all_wordpress_feeds() {
    wp_redirect( home_url(), 301 );
    exit;
}

// Disable all standard feed types
add_action( 'do_feed', 'disable_all_wordpress_feeds', 1 );
add_action( 'do_feed_rdf', 'disable_all_wordpress_feeds', 1 );
add_action( 'do_feed_rss', 'disable_all_wordpress_feeds', 1 );
add_action( 'do_feed_rss2', 'disable_all_wordpress_feeds', 1 );
add_action( 'do_feed_atom', 'disable_all_wordpress_feeds', 1 );
add_action( 'do_feed_rss2_comments', 'disable_all_wordpress_feeds', 1 );
add_action( 'do_feed_atom_comments', 'disable_all_wordpress_feeds', 1 );

// Remove feed links from the site's <head> HTML
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
