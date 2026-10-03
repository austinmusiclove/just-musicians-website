<?php

/**
 * Handle secure user logout without template nonce errors
 *
 * A nonce rendered into a cached page can expire or belong to a previous session,
 * which makes wp_logout_url() links fail the check_admin_referer( 'log-out' ) test in
 * wp-login.php. This endpoint carries no nonce, so it stays valid in cached markup.
 */
function handle_secure_theme_logout() {
    wp_logout();
    wp_safe_redirect( home_url() );
    exit;
}
add_action( 'admin_post_secure_theme_logout', 'handle_secure_theme_logout' );
add_action( 'admin_post_nopriv_secure_theme_logout', 'handle_secure_theme_logout' );
