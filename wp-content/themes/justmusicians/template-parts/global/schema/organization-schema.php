<?php

$schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'Organization',
    '@id'      => home_url(),
    'name'     => 'HireMusicians.com',
    'url'         => home_url(),
    'email'       => 'john@hiremusicians.com',
    'description' => 'HireMusicians.com is an online live musician directory based in Austin, Texas.',
    'logo'        => get_template_directory_uri() . '/lib/images/logos/hm-logo-emblem-white-1.svg',
    'sameAs'   => [
        'https://www.instagram.com/hiremoremusicians',
    ],
    'contactPoint' => [
        [
            '@type'       => 'ContactPoint',
            'email'       => 'john@hiremusicians.com',
            'contactType' => 'customer service',
            'areaServed'  => 'US',
            'availableLanguage' => 'English',
        ],
    ],
];

?>
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
