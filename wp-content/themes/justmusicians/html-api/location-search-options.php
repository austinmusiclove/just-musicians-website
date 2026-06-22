<?php
$search_term = stripslashes($_GET['location']);
$update_func = stripslashes($_GET['update_func'] ?? 'updateLocation');
if (empty($search_term)) {
    get_template_part('template-parts/search/active-search/location-search-state-1', '', [
        'message' => 'Start typing a city or postal code..'
    ]);
} else if (strlen($search_term) < 2) {
    get_template_part('template-parts/search/active-search/location-search-state-1', '', [
        'message' => 'Start typing a city or postal code..'
    ]);
} else {
    $result = hm_location_search($search_term);
    if (count($result) == 0) {
        get_template_part('template-parts/search/active-search/location-search-state-1', '', [
            'message' => 'No Results. Try a city name or valid US or Canada postal code (ex. 78701, A1A)'
        ]);
    } else {
        get_template_part('template-parts/search/active-search/location-search-state-2', '', [
            'locations' => $result,
            'update_func' => $update_func,
        ]);
    }
}
