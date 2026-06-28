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

        $applications[] = [
            'post_id'     => get_the_ID(),
            'title'       => get_post_meta(get_the_ID(), 'title', true),
            'description' => get_post_meta(get_the_ID(), 'description', true),
            'permalink'   => get_permalink(),
        ];
    }

    wp_reset_postdata();

    return [
        'applications'  => $applications,
        'max_num_pages' => $max_num_pages,
        'next_page'     => $next_page,
    ];
}
