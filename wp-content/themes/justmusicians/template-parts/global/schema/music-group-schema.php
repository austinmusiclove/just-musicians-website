<?php

$location_data  = (!empty($args['zip_code']) && function_exists('hm_location_get_by_pc')) ? hm_location_get_by_pc($args['zip_code']) : null;
$addressCountry = !empty($location_data->country) ? $location_data->country : 'US';
$addressRegion  = !empty($location_data->state_code) ? $location_data->state_code : get_state_code($args['state'] ?? '');

$schema = [
    '@context'        => 'https://schema.org',
    '@type'           => 'MusicGroup',
    '@id'             => $args['url'] ?? '',
    'name'            => $args['name'] ?? '',
    'url'             => $args['url'] ?? '',
    'image'           => $args['image'] ?? '',
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
    'aggregateRating' => (!empty($args['rating']) && !empty($args['review_count'])) ? [
        '@type'       => 'AggregateRating',
        'ratingValue' => $args['rating'],
        'reviewCount' => $args['review_count'],
        'bestRating'  => '5',
        'worstRating' => '1',
    ] : null,
];

// Add review schema entries
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

        $schema['review'][] = [
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

// Remove any null, empty string, or empty array values recursively
$schema = clean_schema_array($schema);

?>
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
