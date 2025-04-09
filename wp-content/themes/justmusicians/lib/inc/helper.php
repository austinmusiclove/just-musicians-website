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

function get_spotify_artist_id_from_url($spotify_artist_url) {
    if (preg_match('/\/artist\/([A-Za-z0-9]{22})/', $spotify_artist_url, $matches)) {
        return $matches[1];
    } else {
        return '';
    }
}
?>
