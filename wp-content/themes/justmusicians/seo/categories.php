<?php

function get_seo_categories_for_location($location) {
    $categories_by_location = [
        'austin-tx' => [
            'country-band', 'cover-band', 'dj', 'funk-band', 'jam-band', 'jazz-trio', 'metal-band',
            'party-band', 'punk-band', 'rapper', 'rock-band', 'singer-songwriter', 'solo-artist',
            'tribute-band', 'wedding-band',
        ],
    ];
    return $categories_by_location[$location] ?? [];
}

function get_seo_category_plural_name($slug) {
    $plural_names = [
        'country-band'      => 'Country Bands',
        'cover-band'        => 'Cover Bands',
        'dj'                => 'DJs',
        'funk-band'         => 'Funk Bands',
        'jam-band'          => 'Jam Bands',
        'jazz-trio'         => 'Jazz Trios',
        'metal-band'        => 'Metal Bands',
        'party-band'        => 'Party Bands',
        'punk-band'         => 'Punk Bands',
        'rapper'            => 'Rappers',
        'rock-band'         => 'Rock Bands',
        'singer-songwriter' => 'Singer-Songwriters',
        'solo-artist'       => 'Solo Artists',
        'tribute-band'      => 'Tribute Bands',
        'wedding-band'      => 'Wedding Bands',
    ];
    if (isset($plural_names[$slug])) {
        return $plural_names[$slug];
    }
    $term = get_term_by('slug', $slug, 'mcategory');
    return ($term and !is_wp_error($term)) ? $term->name . 's' : $slug;
}
