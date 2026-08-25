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
        'paged'          => $page,
        'posts_per_page' => 10,
    ];

    if (!empty($args['post_ids'])) {
        $query_args['post__in'] = $args['post_ids'];
        $query_args['orderby']  = 'post__in';
    } else {
        $query_args['author'] = get_current_user_id();
    }

    $query = new WP_Query($query_args);
    $max_num_pages = $query->max_num_pages;

    while ($query->have_posts()) {
        $query->the_post();

        $application_id = get_the_ID();
        $app_submissions = get_applicants($application_id, [
            'status' => 'active',
            'nopaging' => true,
        ]);

        $applications[] = [
            'post_id'            => $application_id,
            'title'              => get_post_meta($application_id, 'title', true),
            'description'        => get_post_meta($application_id, 'description', true),
            'permalink'          => get_permalink(),
            'app_submission_ids' => $app_submissions['submission_ids'],
        ];
    }

    wp_reset_postdata();

    return [
        'applications'  => $applications,
        'max_num_pages' => $max_num_pages,
        'next_page'     => $next_page,
    ];
}
