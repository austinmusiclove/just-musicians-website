<?php

function get_default_options($filter) {
    // This is the singluar place to adjust the default filter options
    // Each array does not have to have the same number of options
    $default_options = [
        'category' => ['Band', 'DJ', 'Solo Artist', 'Cover Band'],
        'genre' => ['Folk', 'Hip Hop', 'Latin', 'Soul'],
        'subgenre' => ['Americana', 'Punk Rock', 'Honky Tonk', '90s Covers'],
        'instrumentation' => ['Guitar', 'Vocals', 'Piano', 'Saxophone'],
        'setting' => ['Wedding', 'Festival', 'Hotel', 'Jazz Club'],
    ];
    return $default_options[$filter];
}

function get_checkbox_ref_string($input_name, $label) {
    return strtolower($input_name) . preg_replace("/[^A-Za-z0-9]/", '', $label);
}

// returns a json encoded representation of an array using single quote to encapsulate strings and escaping single quotes within strings
// use this to insert an array into an html attribute value which will be encased in double quotes
function array_2_doublequote_str($arr) {
    return str_replace('"', "'", str_replace("'", "\'", json_encode($arr)));
}
?>
