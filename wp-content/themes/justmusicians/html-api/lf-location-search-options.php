<?php
$search_term = stripslashes($_GET['pc_search']);
if (empty($search_term)) {
    get_template_part('template-parts/search/lf-location-search-state-1');
} else if (strlen($search_term) < 2) {
    get_template_part('template-parts/search/lf-location-search-state-1');
} else {
    $result = hm_location_search($search_term, false);
    get_template_part('template-parts/search/lf-location-search-state-2', '', [
        'locations' => $result,
    ]);
}
