<?php

$items = $args['items'] ?? [];

$main_entity = [];
foreach ($items as $item) {
    if (empty($item['question']) || empty($item['answer'])) { continue; }
    $main_entity[] = [
        '@type'          => 'Question',
        'name'           => $item['question'],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text'  => wp_strip_all_tags($item['answer']),
        ],
    ];
}

if (empty($main_entity)) { return; }

$schema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => $main_entity,
];

?>
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
