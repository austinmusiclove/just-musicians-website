<?php

$items = $args['items'] ?? [];

if (empty($items)) {
    return;
}

$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [],
];

$position = 1;
foreach ($items as $item) {
    $element = [
        '@type' => 'ListItem',
        'position' => $position,
        'name' => $item['label'],
    ];
    $element['item'] = !empty($item['url']) ? $item['url'] : home_url($_SERVER['REQUEST_URI']);
    $schema['itemListElement'][] = $element;
    $position++;
}

?>

<script type="application/ld+json">
<?php echo wp_json_encode($schema); ?>
</script>
