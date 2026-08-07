<?php

function get_seo_meta_description( $category_slug, $location_label ) {
    $descriptions = [
        'country-band'       => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a country band here on HireMusicians.com for down-home, boot-stomping energy. From hoedowns to weddings, a country band fills the room with hits everyone can sing along to.',
        'cover-band'         => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a cover band here on HireMusicians.com for a setlist packed with crowd-pleasing hits. With songs everyone knows, a cover band keeps every guest on the dance floor.',
        'dj'                 => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a DJ here on HireMusicians.com for someone who knows how to keep the party going. From weddings to private parties, a DJ reads the room and keeps the dance floor full all night.',
        'funk-band'          => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a funk band here on HireMusicians.com for deep grooves and brassy horns that get people moving. With infectious rhythm and high energy, a funk band brings the party to any occasion.',
        'jam-band'           => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a jam band here on HireMusicians.com for free-flowing sets and one-of-a-kind improvisation. From festivals to private events, a jam band takes the crowd on a musical journey.',
        'jazz-trio'          => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a jazz trio here on HireMusicians.com for sophisticated, smooth sound. A jazz trio sets a refined, laid-back tone perfect for cocktail hours, weddings, and private parties.',
        'metal-band'         => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a metal band here on HireMusicians.com for loud, high-energy performances. With powerful riffs and serious intensity, a metal band brings the edge to any event.',
        'party-band'         => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a party band here on HireMusicians.com for high-energy sets that keep the celebration going. Playing all the crowd favorites, a party band delivers an unforgettable night for weddings and private parties.',
        'punk-band'          => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a punk band here on HireMusicians.com for raw energy and unapologetic attitude. With fast, uncompromising rock, a punk band brings an edge to any party or show.',
        'rapper'             => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a rapper here on HireMusicians.com for high-energy hip-hop that gets the crowd hyped. From parties to private events, a rapper brings the heat and keeps everyone moving.',
        'rock-band'          => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a rock band here on HireMusicians.com for powerful riffs and an unforgettable show. Whether it\'s a wedding or private party, hire a rock band that delivers a standout performance.',
        'singer-songwriter'  => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a singer-songwriter here on HireMusicians.com for intimate, heartfelt performances. With captivating originals and honest storytelling, a singer-songwriter creates a personal soundtrack for any occasion.',
        'solo-artist'        => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a solo artist here on HireMusicians.com for a versatile, polished performance. Compact setup, maximum impact — a solo artist makes any venue feel special without the full-band footprint.',
        'tribute-band'       => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a tribute band here on HireMusicians.com for the songs you love, performed live. Recreating the magic of the originals, a tribute band is a crowd favorite for festivals, weddings, and private parties.',
        'wedding-band'       => 'Looking to add music to your event in the ' . $location_label . ' area? Hire a wedding band here on HireMusicians.com for the soundtrack to your big day. From the first dance to the last song, a wedding band keeps you and your guests dancing all night.',
    ];

    if ( ! isset( $descriptions[ $category_slug ] ) ) {
        return 'Discover ' . get_seo_category_plural_name( $category_slug ) . ' in ' . $location_label . '. Find local artists to hire for your next live music event.';
    }

    return $descriptions[ $category_slug ];
}

function custom_meta_descriptions() {
    // Check if we are on one of the custom rewrite pages
    $category = get_query_var('seo-category') ?? 0;
    $location = get_query_var('seo-location') ?? 0;
    if ( $category && $location ) {

        $location_data = get_seo_location($location);
        if ( $location_data ) {
            $description = get_seo_meta_description( $category, $location_data['city'] . ', ' . $location_data['state'] );
            echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
        }

    }
}
add_action( 'wp_head', 'custom_meta_descriptions' );
