<?php
if (!defined('ABSPATH')) { exit; }

function user_can_create_inquiry_proposal($event_id) {
    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must be logged in.', ['status' => 401]);
    }

    if (!$event_id || !is_numeric($event_id)) {
        return new WP_Error('invalid_event_id', 'Event ID is required.', ['status' => 400]);
    }

    $user_id   = get_current_user_id();
    $author_id = (int) get_post_field('post_author', $event_id);

    if ((int) $user_id !== $author_id) {
        return new WP_Error('forbidden', 'You are not the author of this event.', ['status' => 403]);
    }

    return true;
}

function user_can_update_proposal($proposal_id) {
    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must be logged in.', ['status' => 401]);
    }

    if (!$proposal_id || !is_numeric($proposal_id)) {
        return new WP_Error('invalid_proposal_id', 'Proposal ID is required.', ['status' => 400]);
    }

    $listing_id = (int) get_post_meta($proposal_id, 'listing', true);
    if (!$listing_id) {
        return new WP_Error('invalid_proposal', 'Proposal has no listing.', ['status' => 400]);
    }

    $user_id       = get_current_user_id();
    $user_listings = get_user_meta($user_id, 'listings', true);

    if (empty($user_listings) || !is_array($user_listings)) {
        return new WP_Error('forbidden', 'You do not own any listings.', ['status' => 403]);
    }

    if (!in_array($listing_id, $user_listings)) {
        return new WP_Error('forbidden', 'You do not own this listing.', ['status' => 403]);
    }

    return true;
}
