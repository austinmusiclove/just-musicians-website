<?php

function get_user_listing_proposals($args = []) {

    $user_id = get_current_user_id();
    if (!$user_id) {
        return [
            'proposals'       => [],
            'max_num_results' => 0,
            'max_num_pages'   => 0,
            'next_page'       => 1,
        ];
    }
    $listing_ids = get_user_meta($user_id, 'listings', true);

    if (empty($listing_ids)) {
        return [
            'proposals'       => [],
            'max_num_results' => 0,
            'max_num_pages'   => 0,
            'next_page'       => 1,
        ];
    }

    $proposal_ids = hm_get_proposals_by_listing_ids($listing_ids);

    $page            = (!empty($args['page']) && is_numeric($args['page'])) ? (int) $args['page'] : 1;
    $page_size       = (!empty($args['page_size']) && is_numeric($args['page_size'])) ? (int) $args['page_size'] : 10;
    $max_num_results = count($proposal_ids);
    $max_num_pages   = (int) ceil($max_num_results / $page_size);
    $next_page       = $page < $max_num_pages ? $page + 1 : 0;
    $offset          = ($page - 1) * $page_size;
    $page_ids        = array_slice($proposal_ids, $offset, $page_size);

    $proposals = get_proposals_with_data($page_ids);

    return [
        'proposals'       => $proposals,
        'max_num_results' => $max_num_results,
        'max_num_pages'   => $max_num_pages,
        'next_page'       => $next_page,
    ];
}
