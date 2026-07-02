<?php

function get_applicants($application_id, $args = []) {

    if (!get_post($application_id) || get_post_type($application_id) !== 'application') {
        return [
            'submission_ids' => [],
            'max_num_pages'  => 0,
            'page'           => 1,
        ];
    }

    $no_paging = !empty($args['nopaging']);

    $page = !empty($args['page']) ? max(1, (int) $args['page']) : 1;

    $meta_query = [
        [
            'key'   => 'application',
            'value' => $application_id,
        ],
    ];

    if (!empty($args['status']) && $args['status'] !== 'all') {
        $meta_query[] = [
            'key'   => 'status',
            'value' => sanitize_text_field($args['status']),
        ];
    }

    $query = new WP_Query([
        'post_type'      => 'app_submission',
        'post_status'    => 'publish',
        'fields'         => 'ids',
        'posts_per_page' => $no_paging ? -1 : 10,
        'paged'          => $no_paging ? 1 : $page,
        'meta_query'     => $meta_query,
        'orderby'        => 'modified',
        'order'          => 'DESC',
    ]);

    return [
        'submission_ids'  => $query->posts,
        'max_num_pages'   => $no_paging ? 1 : $query->max_num_pages,
        'max_num_results' => $query->found_posts,
        'page'            => $no_paging ? 1 : $page,
    ];
}
