<?php

function get_user_applications($args) {

    $sanitized_page  = (!empty($args['page'])) ? sanitize_text_field($args['page']) : null;
    $page            = (is_numeric($sanitized_page) and (int)$sanitized_page) ? (int)$sanitized_page : 1;
    $next_page       = $page + 1;
    $max_num_pages   = 0;

    $applications = [];
    $query_args = [
        'post_type'      => 'application',
        'post_status'    => 'publish',
        'author'         => get_current_user_id(),
        'paged'          => $page,
        'posts_per_page' => 10,
    ];

    $query = new WP_Query($query_args);
    $max_num_pages = $query->max_num_pages;

    while ($query->have_posts()) {
        $query->the_post();

        $application_id = get_the_ID();

        $count_query = new WP_Query([
            'post_type'      => 'app_submission',
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'posts_per_page' => -1,
            'no_found_rows'  => true,
            'meta_query'     => [
                ['key' => 'application', 'value' => $application_id],
                ['key' => 'status',      'value' => 'active'],
            ],
        ]);

        $applications[] = [
            'post_id'         => $application_id,
            'title'           => get_post_meta($application_id, 'title', true),
            'description'     => get_post_meta($application_id, 'description', true),
            'permalink'       => get_permalink(),
            'applicant_count' => (int) $count_query->post_count,
        ];
    }

    wp_reset_postdata();

    return [
        'applications'  => $applications,
        'max_num_pages' => $max_num_pages,
        'next_page'     => $next_page,
    ];
}
