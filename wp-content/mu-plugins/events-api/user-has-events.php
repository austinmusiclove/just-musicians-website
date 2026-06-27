<?php

function user_has_events( $user_id ) {
    global $wpdb;

    // Use SELECT 1 and LIMIT 1 for maximum efficiency
    $query = $wpdb->prepare(
        "SELECT 1 FROM {$wpdb->posts} WHERE post_author = %d AND post_type = 'event' AND post_status = 'publish' LIMIT 1",
        $user_id
    );

    // var_dump will return string "1" if found, or null if not found
    return (bool) $wpdb->get_var( $query );
}
