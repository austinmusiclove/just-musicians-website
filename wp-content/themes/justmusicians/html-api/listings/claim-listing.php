<?php

$listing_id = get_query_var('listing-id');
$user_id    = get_current_user_id();

if (!$user_id) {
    echo get_template_part('template-parts/listing-page/parts/claim-button', '', [
        'post_id'    => $listing_id,
        'error_toast'=> 'You must be logged in to claim a listing.',
    ]);
    exit;
}

if (!$listing_id) {
    echo get_template_part('template-parts/listing-page/parts/claim-button', '', [
        'post_id'    => $listing_id,
        'error_toast'=> 'Invalid listing ID.',
    ]);
    exit;
}

$unclaimed = get_post_meta($listing_id, 'unclaimed', true);
if (!$unclaimed) {
    echo get_template_part('template-parts/listing-page/parts/claim-button', '', [
        'post_id'    => $listing_id,
        'error_toast'=> 'This listing has already been claimed.',
    ]);
    exit;
}

send_admin_claim_listing_email($user_id, $listing_id);

echo get_template_part('template-parts/listing-page/parts/claim-button-sent', '', [
    'success_toast' => 'Claim Request Sent',
    'btn_text'      => 'Claim Request Sent',
]);
