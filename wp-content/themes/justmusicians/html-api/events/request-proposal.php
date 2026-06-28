<?php

$event_id = get_query_var('event-id');
$listing_id = get_query_var('listing-id');

// Request proposal
$result = request_proposal($event_id, $listing_id);
if ( is_wp_error($result) ) {
    $message = 'Error: ' . $result->get_error_message();
    echo get_template_part('template-parts/cards/card-components/request-proposal-btn', '', [
        'event_id'    => $event_id,
        'listing_id'  => $listing_id,
        'error_toast' => $message,
        'btn_text'    => 'Send',
    ]);
    exit;
}

// Success Response
echo get_template_part('template-parts/cards/card-components/request-proposal-btn-sent', '', [
    'success_toast' => 'Inquiry Sent Successfully',
    'btn_text'      => 'Sent',
]);
