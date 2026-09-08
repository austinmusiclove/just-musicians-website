<?php

$offers = $args['offers'] ?? [];
$page_url = get_permalink();

$main_entity = [];
$position = 1;
foreach ($offers as $offer) {
    $schema_name = $offer['schema_name'] ?? $offer['name'] ?? '';
    if (empty($schema_name)) { continue; }

    $schema_description = $offer['schema_description'] ?? $offer['description'] ?? '';

    $price = isset($offer['price']) ? str_replace('$', '', $offer['price']) : '';

    $item = [
        '@type'       => 'Service',
        'name'        => $schema_name,
        'description' => wp_strip_all_tags($schema_description),
    ];

    $main_entity[] = [
        '@type'    => 'Offer',
        'position' => $position++,
        'item'     => $item,
        'price'    => $price,
        'priceCurrency' => 'USD',
        'availability'  => 'https://schema.org/InStock',
        'url'      => $page_url,
    ];
}

if (empty($main_entity)) { return; }

$schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'ItemList',
    'name'     => 'Talent Buyer Pro Pricing',
    'itemListElement' => $main_entity,
];

?>
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
