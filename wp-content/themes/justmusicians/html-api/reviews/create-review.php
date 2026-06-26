<?php

// Create Review
$reviewee_id      = get_query_var('reviewee-id');
$review_post_type = get_query_var('review-post-type');
$args             = get_sanitized_review_args($review_post_type, $reviewee_id);

$result = create_review($args);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo '<span x-init="_handleCreateReviewError(\'' . $message . '\')"></span>';
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
    exit;
}

// Success Response
echo '<span x-init="_handleCreateReviewSuccess()"></span>';
echo '<span x-init="$dispatch(\'success-toast\', { \'message\': \'' . 'Review Created Successfully' . '\'})"></span>';
echo '<span x-init="$dispatch(\'fetch-reviews\')"></span>';
