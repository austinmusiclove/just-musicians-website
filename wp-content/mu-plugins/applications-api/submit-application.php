<?php
function submit_application($args, $logged_in = true) {

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
    $proposal_status = $logged_in ? 'publish' : 'pending';
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
                ], $proposal_status);
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

    // Send notifications and emails if the submission is published (in case of not logged in user submission, it will not be published)
    if ($logged_in) {
        send_application_submitted_successfully_email(get_current_user_id(), $application_id);
        $application_author = get_post_field('post_author', $application_id);
        send_creator_new_applicant_email($application_author, $application_id);
        add_new_applicant_notification($application_author, $post_id);
    }

    return $post_id;
}

function submit_new_listing_application($submission_args, $listing_args) {

    $user_id = get_current_user_id();
    if (!$user_id) {
        return submit_new_listing_application_anon($submission_args, $listing_args);
    }

    // Create listing
    $listing_id = _create_listing($listing_args);
    if (is_wp_error($listing_id)) {
        return $listing_id;
    }
    $submission_args['meta_input']['listing'] = $listing_id;

    return submit_application($submission_args, true);
}

// For non logged in users
function submit_new_listing_application_anon($submission_args, $listing_args) {

    // Set status of new listing and app submission to pending
    $submission_args['status'] = 'pending';
    $listing_args['status']    = 'pending';

    // Create listing
    $listing_id = _create_listing($listing_args);
    if (is_wp_error($listing_id)) {
        return $listing_id;
    }
    $submission_args['meta_input']['listing'] = $listing_id;

    $post_id = submit_application($submission_args, false);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    // Create tmp_code for publishing listing and application submission and proposals after sign up
    $expiration = time() + 31536000; // one year
    $tmp_code = create_temporary_code($expiration, [ 'listings' => [$listing_id] ]);
    if (is_wp_error($tmp_code)) {
        return $tmp_code;
    }

    // Send email to non logged in user to direct them to sign up to complete their application
    $application_id = $submission_args['meta_input']['application'] ?? 0;
    $submitter_email = $listing_args['meta_input']['email'] ?? '';
    $sign_up_link = site_url('/musician-application/' . $application_id) . '?lpc=' . $tmp_code;
    send_sign_up_to_complete_application_email($submitter_email, $application_id, $tmp_code);

    return [
        'post_id'      => $post_id,
        'sign_up_link' => $sign_up_link ,
    ];
}
