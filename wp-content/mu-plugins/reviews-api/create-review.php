<?php

function create_review($args) {

    // If this is a buyer review and the author matches the reviewee, this is a user reviewing themselves. Disallow
    if ($args['post_type'] == 'buyer_review' and $args['meta_input']['author'] == $args['meta_input']['reviewee']) {
        return new WP_Error('creation_failed', 'Failed to create review. You are not allowed to review yourself.');
    }

    // Create post
    $review_id = wp_insert_post($args);
    if (is_wp_error($review_id) || !$review_id) {
        return new WP_Error('creation_failed', 'Failed to create review.');
    }

    // If review is post type listing_review send an email to the listing owners to let them know they got a review and to invite them to leave a buyer review
    if ($args['post_type'] == 'listing_review') {
        send_buyer_review_invites($args['meta_input']['reviewee'], $args['meta_input']['author']);
    }

    return [
        'post_id'   => $review_id,
    ];
}

function send_buyer_review_invites($listing_id, $author_id) {
    $author_name = get_display_name($author_id);
    $listing_name = get_post_meta( $listing_id, 'name', true );
    $buyer_review_url = create_buyer_review_invite_url($author_id);
    $listing_owners = get_listing_owners($listing_id);
    foreach ($listing_owners as $recipient_user_id) {
        send_listing_owner_review_invite_email($recipient_user_id, $author_name, $listing_name, $buyer_review_url);
    }
}

function create_buyer_review_invite_url($buyer_id) {
    //$expiration = time() + 60; // one minute
    //$expiration = time() + 2628000; // one month
    //$expiration = time() + 31536000; // one year
    //$tmp_code = create_temporary_code($expiration);
    //return site_url("/buyers/{$buyer_id}/?mdl=review&tc={$tmp_code}");
    return site_url("/buyers/{$buyer_id}/?mdl=review");
}
