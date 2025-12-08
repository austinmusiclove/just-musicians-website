
<?php

    // Create Google Maps Embed Url
    $venue_name       = get_field('name');
    $street_address   = get_field('street_address');
    $address_locality = get_field('address_locality');
    $address_region   = get_field('address_region');
    $postal_code      = get_field('postal_code');
    $map_query        = urlencode("{$venue_name}, {$street_address}, {$address_locality}, {$address_region} {$postal_code}");
    $google_maps_url  = "https://www.google.com/maps/embed/v1/place?key=" . GOOGLE_MAPS_API_KEY . "&q={$map_query}";

    // Add center if lat lon are present
    $latitude         = get_field('latitude');
    $longitude        = get_field('longitude');
    if (!empty($latitude) and !empty($longitude)) {
        $map_center      = urlencode("{$latitude}, {$longitude}");
        $google_maps_url = $google_maps_url . "&center={$map_center}";
    }

?>


<iframe class="w-full" width="100%" height="350"
    frameborder="0" style="border:0"
    referrerpolicy="no-referrer-when-downgrade"
    src="<?php echo $google_maps_url; ?>"
    allowfullscreen
></iframe>
