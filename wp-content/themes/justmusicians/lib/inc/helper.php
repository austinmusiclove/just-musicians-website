<?php

function get_default_options($filter) {
    // This is the singluar place to adjust the default filter options
    // Each array does not have to have the same number of options
    $default_options = [
        'category' =>        ['Band', 'DJ', 'Solo Artist', 'Cover Band'],
        'genre' =>           ['Folk', 'Hip Hop', 'Latin', 'Soul'],
        'subgenre' =>        ['Americana', 'Punk Rock', 'Honky Tonk', '90s Covers'],
        'instrumentation' => ['Guitar', 'Vocals', 'Piano', 'Saxophone'],
        'setting' =>         ['Wedding', 'Festival', 'Hotel', 'Jazz Club'],
    ];
    return $default_options[$filter];
}

function get_checkbox_ref_string($input_name, $label) {
    $clean_label = html_entity_decode($label, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Handles html encoded characters like &amp; for &
    $cleaned =  preg_replace('/[^A-Za-z0-9]/', '', $clean_label); // remove special characters
    return 'cr' . preg_replace('/[^A-Za-z0-9]/', '', $clean_label); // append cr to the begginning to avoid having a result that starts with a number which would not work as a js variable
}

// Modifies an array so that it can be injected into doublequotes in an html attribute
// unserialize, json encode, then encode only " and '
function clean_arr_for_doublequotes($arr) {
    return html_entity_decode(htmlspecialchars(json_encode(maybe_unserialize($arr), JSON_UNESCAPED_SLASHES), ENT_QUOTES , 'UTF-8'), ENT_NOQUOTES, 'UTF-8');
}

// Modifies a string so that it can be injected into doublequotes in an html attribute
function clean_str_for_doublequotes($string) {
    return str_replace("'", "\'", str_replace("\\", "\\\\", htmlspecialchars($string, ENT_COMPAT, 'UTF-8')));
}

function get_terms_decoded($taxonomy, $fields, $search=false) {
    $args = [
        'taxonomy' => $taxonomy,
        'fields' => 'names',
        'hide_empty' => false,
    ];
    if ($search) { $args['search'] = $search; }
    $terms = get_terms($args);
    return array_map(function($term) { return html_entity_decode($term, ENT_QUOTES | ENT_HTML5, 'UTF-8'); }, $terms);
}
?>
