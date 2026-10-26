<?php
/**
 * Add defer attribute to Google Site Kit gtag.js script tag.
 */
function add_defer_to_sitekit_gtag( $tag, $handle, $src ) {
    // Target Site Kit's gtag script handle or any script loading /gtag/js
    if ( strpos( $src, 'googletagmanager.com/gtag/js' ) !== false ) {
        // Prevent duplicate attributes if already present
        if ( false === strpos( $tag, 'defer' ) ) {
            $tag = str_replace( ' src=', ' defer="defer" src=', $tag );
        }
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'add_defer_to_sitekit_gtag', 10, 3 );
