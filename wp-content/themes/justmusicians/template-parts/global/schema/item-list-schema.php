<?php

$listings = $args['listings'] ?? [];

$item_list_elements = [];
$position = 1;
foreach ($listings as $listing) {
    if (empty($listing['permalink'])) { continue; }
    $item = [
        '@type' => 'MusicGroup',
        'name'  => $listing['name'],
        'url'   => $listing['permalink'],
    ];
    if (!empty($listing['thumbnail_url'])) {
        $item['image'] = $listing['thumbnail_url'];
    }
    $item_list_elements[] = [
        '@type'    => 'ListItem',
        'position' => $position++,
        'item'     => $item,
    ];
}

if (empty($item_list_elements)) { return; }

$schema = [
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'itemListElement' => $item_list_elements,
];

?>
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
