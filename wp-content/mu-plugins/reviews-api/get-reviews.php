<?php

function get_reviews($review_post_type, $reviewee_id) {
    $args = [
        'post_type'      => $review_post_type,
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [
            [
                'key'   => 'reviewee',
                'value' => $reviewee_id,
                'compare' => '='
            ]
        ]
    ];

    $query = new WP_Query($args);
    $reviews = [];

    if ($query->have_posts()) {
        foreach ($query->posts as $post) {

            // get author user id
            $author_id = get_post_meta($post->ID, 'author', true);
            $author_info = get_userdata($author_id);
            $author_name = clean_display_name($author_info->display_name);
            $author_org = get_user_meta($author_id, 'organization', true);
            $author_position = get_user_meta($author_id, 'position', true);
            $author_avatar = get_user_meta($author_id, 'avatar', true);
            $avatar_avatar = get_field('avatar', 'user_' . $author_id);
            $author_image_url = '';
            if (!empty($author_avatar)) {
                $author_image_url = wp_get_attachment_url($author_avatar);
            } else {
                // Fallback to Gravatar
                $author_image_url = get_avatar_url($author_id);
            }

            $reviews[] = [
                'rating'              => get_post_meta($post->ID, 'rating', true),
                'review'              => nl2br(get_post_meta($post->ID, 'review', true)),
                'author_name'         => $author_name,
                'author_organization' => $author_org,
                'author_position'     => $author_position,
                'author_image_url'    => $author_image_url,
            ];
        }
    }

    wp_reset_postdata();
    return $reviews;
}
