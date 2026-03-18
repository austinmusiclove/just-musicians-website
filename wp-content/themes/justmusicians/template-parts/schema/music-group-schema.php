<?php

$schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'MusicGroup',
    '@id'         => $args['url'] ?? '',
    'name'        => $args['name'] ?? '',
    'url'         => $args['url'] ?? '',
    'image'       => $args['image'] ?? '',
    'description' => wp_strip_all_tags($args['description'] ?? ''),
];

if (!empty($args['genre'])) {
    $schema['genre'] = $args['genre'];
}

if (!empty($args['city']) || !empty($args['state'])) {
    $schema['location'] = [
        '@type'   => 'Place',
        'address' => [
            '@type'           => 'PostalAddress',
            'addressLocality' => $args['city'] ?? '',
            'addressRegion'   => get_state_code($args['state'] ?? ''),
            'addressCountry'  => 'US'
        ]
    ];
}

if (!empty($args['phone'])) {
    $schema['telephone'] = $args['phone'];
}

if (!empty($args['email'])) {
    $schema['email'] = $args['email'];
}

if (!empty($args['sameAs'])) {
    $schema['sameAs'] = array_values(array_filter($args['sameAs']));
}

if (!empty($args['rating']) && !empty($args['review_count'])) {
    $schema['aggregateRating'] = [
        '@type'       => 'AggregateRating',
        'ratingValue' => $args['rating'],
        'reviewCount' => $args['review_count'],
        'bestRating'  => '5',
        'worstRating' => '1',
    ];
}

if (!empty($args['reviews'])) {
    foreach ($args['reviews'] as $review) {
        $schema['review'][] = [
            '@type'         => 'Review',
            'author'        => [
                '@type' => 'Person',
                'name'  => $review['author_name'],
            ],
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

// Clean up empty values at the root level just in case
$schema = array_filter($schema);

?>
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
