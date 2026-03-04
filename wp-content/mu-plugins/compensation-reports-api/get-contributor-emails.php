<?php

function get_contributor_emails() {
    $query = new WP_Query([
        'post_type'      => 'comp_report',
        'post_status'    => 'publish',
        'nopaging'       => true,
        'fields'         => 'ids',
    ]);

    $emails = [];

    if ( $query->have_posts() ) {
        foreach ( $query->posts as $post_id ) {
            $author_email = get_field( 'author_email', $post_id );

            if ( ! empty( $author_email ) ) {
                $emails[] = $author_email;
            } else {
                $post = get_post( $post_id );
                if ( $post && $post->post_author ) {
                    $user_email = get_the_author_meta( 'user_email', $post->post_author );
                    if ( ! empty( $user_email ) ) {
                        $emails[] = $user_email;
                    }
                }
            }
        }
        wp_reset_postdata();
    }

    return array_values( array_unique( array_filter( $emails ) ) );
}
