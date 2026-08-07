
<?php

$location_data  = (!empty($args['zip_code']) && function_exists('hm_location_get_by_pc')) ? hm_location_get_by_pc($args['zip_code']) : null;
$addressCountry = !empty($location_data->country) ? $location_data->country : 'US';
$addressRegion  = !empty($location_data->state_code) ? $location_data->state_code : get_state_code($args['state'] ?? '');

// Add image schema
$images = [];
if (!empty($args['thumbnail'])) {
    $image_schema = ['@type' => 'ImageObject', 'contentUrl' => $args['thumbnail']];
    if (!empty($args['representative_of_page'])) {
        $image_schema['representativeOfPage'] = true;
    }
    $images[] = $image_schema;
}
if (!empty($args['images']) && is_array($args['images'])) {
    foreach ($args['images'] as $img_url) {
        if (empty($img_url)) { continue; }
        $images[] = ['@type' => 'ImageObject', 'contentUrl' => $img_url];
    }
}

// Add review schema entries
$reviews_schema = [];
if (!empty($args['reviews'])) {
    foreach ($args['reviews'] as $review) {
        $author_schema = [
            '@type'    => 'Person',
            'name'     => $review['author_name'],
            'jobTitle' => $review['author_position'] ?? '',
            'worksFor' => [
                '@type' => 'Organization',
                'name'  => $review['author_organization'] ?? '',
            ],
        ];

        $reviews_schema[] = [
            '@type'         => 'Review',
            'author'        => $author_schema,
            'datePublished' => $review['date'],
            'reviewBody'    => wp_strip_all_tags($review['review']),
            'reviewRating'  => [
                '@type'       => 'Rating',
                'ratingValue' => $review['rating'],
                'bestRating'  => '5',
                'worstRating' => '1',
            ],
        ];
    }
}

$videos_schema = [];
// Add video schema entries
if (!empty($args['videos'])) {
    foreach ($args['videos'] as $video) {
        $video_id  = $video['video_id'] ?? '';
        $video_url = $video['url'] ?? '';
        if (empty($video_id) && empty($video_url)) { continue; }

        $video_schema = [
            '@type'       => 'VideoObject',
            'name'        => ($args['name'] ?? '') . ' — Video',
            'contentUrl'  => $video_url,
            'uploadDate'  => $video['post_date'] ?? '',
        ];
        if ($video_id) {
            $video_schema['embedUrl']     = 'https://www.youtube.com/embed/' . $video_id;
            $video_schema['thumbnailUrl'] = 'https://img.youtube.com/vi/' . $video_id . '/hqdefault.jpg';
        }

        $videos_schema[] = $video_schema;
    }
}

$area_served = null;
if (!empty($args['area_served']) && is_array($args['area_served'])) {
    $area_served = [
        '@type' => 'Place',
        'name'  => trim(($args['area_served']['city'] ?? '') . ', ' . ($args['area_served']['state'] ?? ''), ', '),
    ];
    if (!empty($args['area_served']['lat']) && !empty($args['area_served']['lng'])) {
        $area_served['geo'] = [
            '@type'       => 'GeoCircle',
            'geoMidpoint' => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $args['area_served']['lat'],
                'longitude' => $args['area_served']['lng'],
            ],
            'geoRadius'   => 40,
        ];
    }
}

$schema = [
    '@context'        => 'https://schema.org',
    '@type'           => 'MusicGroup',
    '@id'             => $args['url'] ?? '',
    'name'            => $args['name'] ?? '',
    'url'             => $args['url'] ?? '',
    'image'           => $images,
    'description'     => wp_strip_all_tags($args['description'] ?? ''),
    'genre'           => $args['genre'] ?? '',
    'telephone'       => $args['phone'] ?? '',
    'email'           => $args['email'] ?? '',
    'sameAs'          => array_values(array_filter($args['sameAs'] ?? [])),
    'location'        => (!empty($args['city']) || !empty($args['state'])) ? [
        '@type'   => 'Place',
        'address' => [
            '@type'           => 'PostalAddress',
            'addressLocality' => $args['city'] ?? '',
            'addressRegion'   => $addressRegion,
            'postalCode'      => $args['zip_code'] ?? '',
            'addressCountry'  => $addressCountry,
        ],
    ] : null,
    'areaServed'      => $area_served,
    'aggregateRating' => (!empty($args['rating']) && !empty($args['review_count'])) ? [
        '@type'       => 'AggregateRating',
        'ratingValue' => $args['rating'],
        'reviewCount' => $args['review_count'],
        'bestRating'  => '5',
        'worstRating' => '1',
    ] : null,
    'review'          => $reviews_schema,
    'subjectOf'       => $videos_schema,
];

// Remove any null, empty string, or empty array values recursively
$schema = clean_schema_array($schema);

?>
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
