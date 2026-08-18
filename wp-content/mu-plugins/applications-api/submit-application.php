<?php
function submit_application($args) {

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

    // If a submission exists, update it and return
    $submission_id = get_application_submission($application_id, $listing_id);
    if ($submission_id) {
        $args['ID'] = $submission_id;
        return update_user_application_submission($args);
    }

    $post_id = wp_insert_post($args, true);
    if (is_wp_error($post_id)) {
        return $post_id;
    }

    // Send new application notifications and emails
    $application_author = get_post_field('post_author', $application_id);
    send_creator_new_applicant_email($application_author, $application_id);
    add_new_applicant_notification($application_author, $post_id);

    // If the submittor is logged in, send them success email
    if (is_user_logged_in()) {
        send_application_submitted_successfully_email(get_current_user_id(), $application_id);

    // If user is not logged in, send them link to sign up
    } else {
        // Create tmp_code for publishing listing and application submission and proposals after sign up
        $submitter_email = $args['submitter_email'];
        $expiration = time() + 31536000; // one year
        $tmp_code = create_temporary_code($expiration, [ 'listings' => [$listing_id] ]);
        if (is_wp_error($tmp_code)) {
            // Return success but notify admin of error
            send_failed_to_generate_tmp_code_email( 'Submitter email: ' . $submitter_email . "\n" . 'Application ID: ' . $application_id . "\n" . 'Application title: ' . $application_title . "\n", $tmp_code);
            return [ 'post_id' => $post_id, ];
        }

        // Send email to non logged in user to direct them to sign up to complete their application
        $sign_up_link = site_url('/musician-application/' . $application_id) . '/?lic=' . $tmp_code;
        send_sign_up_to_complete_application_email($submitter_email, $application_id, $sign_up_link);
        return [
            'post_id'      => $post_id,
            'sign_up_link' => $sign_up_link ,
        ];
    }

    return [ 'post_id' => $post_id, ];
}

function submit_new_listing_application($submission_args, $listing_args) {

    $user_id = get_current_user_id();
    if (!$user_id) {
        // Set status of new listing to pending
        $listing_args['post_status'] = 'pending';
        $submission_args['submitter_email'] = $listing_args['meta_input']['email'];
    }

    // Create listing
    $listing_id = _create_listing($listing_args);
    if (is_wp_error($listing_id)) {
        return $listing_id;
    }
    $submission_args['meta_input']['listing'] = $listing_id;

    return submit_application($submission_args);
}
