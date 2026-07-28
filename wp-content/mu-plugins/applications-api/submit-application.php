<?php
function submit_application($args) {
    $user_id = get_current_user_id();
    if (!$user_id) {
        return new WP_Error('not_logged_in', 'You must be logged in to perform this function');
    }

    // Require application_id and listing_Id
    $application_id = $args['meta_input']['application'] ?? 0;
    $listing_id     = $args['meta_input']['listing']     ?? 0;
    if (!$application_id) {
        return new WP_Error('missing_required_field', 'Application id missing');
    }
    if (!$listing_id) {
        return new WP_Error('missing_required_field', 'Listing id missing');
    }

    // Create/update proposals for each event's availability
    if (!empty($args['meta_input']['event_availability']) && $listing_id) {
        foreach ($args['meta_input']['event_availability'] as $event_id => $availability) {
            $proposal_id = hm_proposal_exists($listing_id, $event_id);
            if ($proposal_id) {
                update_proposal([
                    'ID'         => $proposal_id,
                    'meta_input' => [
                        'availability' => $availability,
                        'status'       => $availability,
                    ],
                ]);
            } else {
                create_proposal([
                    'event'        => $event_id,
                    'listing'      => $listing_id,
                    'availability' => $availability,
                    'status'       => $availability,
                ]);
            }
        }
    }

    // Set post title
    $application_title = get_post_meta($application_id, 'title', true);
    $listing_name      = get_post_meta($listing_id, 'name', true);
    $args['post_title'] = "$application_id-$listing_id :: $application_title-$listing_name";

    // If a submission exists, update it
    $submission_id = get_application_submission($application_id, $listing_id);
    if ($submission_id) {
        $args['ID'] = $submission_id;
        return update_user_application_submission($args);
    }

    $post_id = wp_insert_post($args, true);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    // Send notifications and emails
    $application_author = get_post_field('post_author', $application_id);
    send_creator_new_applicant_email($application_author, $application_id);
    add_new_applicant_notification($application_author, $post_id);

    return $post_id;
}

function submit_new_listing_application($submission_args, $listing_args) {
    $user_id = get_current_user_id();
    if (!$user_id) {
        return new WP_Error('not_logged_in', 'You must be logged in to perform this function');
    }

    $listing_id = _create_listing($listing_args);
    if (is_wp_error($listing_id)) {
        return $listing_id;
    }

    $submission_args['meta_input']['listing'] = $listing_id;

    return submit_application($submission_args);
}
