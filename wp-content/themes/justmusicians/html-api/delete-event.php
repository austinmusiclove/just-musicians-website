<?php

$post_id = get_query_var('event-id');
$args = ['post_id' => $post_id];

// Delete Event
$post = trash_event($post_id);
if ( is_wp_error($post) ) {
    $message = 'Error: ' . $post->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Success Response
echo '<span x-init="redirect(\'/my-events/?toast=delete\');"></span>'; exit;
