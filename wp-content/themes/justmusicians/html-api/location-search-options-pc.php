<?php
$search_term = stripslashes($_GET['pc_search']);
$update_func = stripslashes($_GET['update_func'] ?? 'updateLocation');
if (empty($search_term)) {
    get_template_part('template-parts/search/active-search-states/pc-search-state-1');
} else if (strlen($search_term) < 2) {
    get_template_part('template-parts/search/active-search-states/pc-search-state-1');
} else {
    $result = hm_location_search($search_term, false);
    get_template_part('template-parts/search/active-search-states/pc-search-state-2', '', [
        'locations' => $result,
        'update_func' => $update_func,
    ]);
}
