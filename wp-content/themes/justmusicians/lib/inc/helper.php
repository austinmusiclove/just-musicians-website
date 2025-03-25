<?php

function get_default_option($filter, $index) {
    switch ($filter) {
        case 'type':
            return ['Band', 'DJ', 'Musician', 'Artist'][$index];
        case 'genre':
            return ['Folk', 'Hip Hop/Rap', 'Latin', 'Soul/RnB'][$index];
        case 'subgenre':
            return ['Acoustic', 'Indie', 'Punk Rock', 'Americana'][$index];
        case 'instrumentation':
            return ['Guitar', 'Vocals', 'Piano', 'Saxophone'][$index];
        case 'setting':
            return ['Dive Bar', 'Wedding', 'Brewery', 'Hotel'][$index];
        case 'tag':
            return ['Full Band', 'Cover Band', 'Original Band', 'Background Music'][$index];
    }
}

function get_checkbox_ref_string($input_name, $label) {
    return strtolower($input_name) . preg_replace("/[^A-Za-z0-9]/", '', $label);
}

?>
