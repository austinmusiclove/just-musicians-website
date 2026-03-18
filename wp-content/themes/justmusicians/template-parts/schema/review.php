<?php
$review_schema = [
    '@context'      => 'https://schema.org/',
    '@type'         => 'Review',
    'itemReviewed'  => [
        '@type' => 'Thing', // Or LocalBusiness/MusicGroup if we want to be highly specific, but Thing is safer generic
        'name'  => wp_strip_all_tags($args['item_reviewed_name'] ?? '')
    ],
    'reviewRating'  => [
        '@type'       => 'Rating',
        'ratingValue' => $args['rating'],
    ],
    'author'        => [
        '@type' => 'Person',
        'name'  => wp_strip_all_tags($args['author_name'] ?? 'Anonymous')
    ],
    'reviewBody'    => wp_strip_all_tags($args['review'] ?? ''),
];

if (!empty($args['date'])) {
    $review_schema['datePublished'] = $args['date'];
}

// Clean up empty values
$review_schema = array_filter($review_schema);

?>
<script type="application/ld+json">
<?php echo json_encode($review_schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
