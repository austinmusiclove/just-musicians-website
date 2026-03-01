<?php

function get_user_comp_reports($user_id) {
    $user_comp_reports = new WP_Query([
        'post_type' => 'comp_report',
        'author' => $user_id,
        'post_status' => 'publish',
        'nopaging' => true,
        'fields' => 'ids',
    ]);
    $post_ids = $user_comp_reports->posts;
    wp_reset_postdata();
    return $post_ids;
}

function has_comp_report($user_id) {
    $comp_reports = get_user_comp_reports($user_id);
    return !empty($comp_reports);
}
