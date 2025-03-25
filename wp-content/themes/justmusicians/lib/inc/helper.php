<?php

function get_default_option($filter, $index) {
    switch ($filter) {
        case 'category':
            return ['Band', 'DJ', 'Musician', 'Cover Band'][$index];
        case 'genre':
            return ['Folk', 'Hip Hop', 'Latin', 'Soul'][$index];
        case 'subgenre':
            return ['Acoustic', 'Indie', 'Punk Rock', 'Americana'][$index];
        case 'instrumentation':
            return ['Guitar', 'Vocals', 'Piano', 'Saxophone'][$index];
        case 'setting':
            return ['Dive Bar', 'Wedding', 'Brewery', 'Hotel'][$index];
    }
}

function get_checkbox_ref_string($input_name, $label) {
    return strtolower($input_name) . preg_replace("/[^A-Za-z0-9]/", '', $label);
}

?>
