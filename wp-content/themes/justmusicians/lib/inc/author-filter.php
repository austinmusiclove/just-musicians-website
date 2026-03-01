<?php

// Allows setting regular users as the author of posts in post editor
add_filter( 'wp_dropdown_users_args', function( $query_args, $r ) {
    $query_args['capability'] = ['subscriber', 'editor', 'administrator'];
}, 10, 2 );
