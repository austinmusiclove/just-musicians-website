<?php

$post_id = get_query_var('application-id');
$args = ['post_id' => $post_id];

// Delete Application
$post = trash_application($post_id);
if ( is_wp_error($post) ) {
    $message = 'Error: ' . $post->get_error_message();
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Success Response
echo '<span x-init="redirect(\'/applications/?toast=delete\');"></span>'; exit;
