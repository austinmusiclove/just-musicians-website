<?php
$search_term = stripslashes($_GET['s']);
if (empty($search_term)) {
    get_template_part('template-parts/search/venues-search-state-1');
} else {
    $result = get_venues([
        'name_search' => $search_term
    ]);
    get_template_part('template-parts/search/venues-search-state-2', '', [
        'venues' => $result,
    ]);
}
