<?php

$about_url = home_url('/about/');

$schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'AboutPage',
    '@id'         => $about_url . '#webpage',
    'url'         => $about_url,
    'name'        => 'About HireMusicians.com',
    'description' => 'Learn about HireMusicians.com, our philosophy, and founder John Filippone.',
    'isPartOf'    => ['@id' => home_url()],
    'about'       => ['@id' => home_url()],
    'mainEntity'  => [
        '@type'       => 'Person',
        '@id'         => $about_url . '#founder',
        'name'        => 'John Filippone',
        'jobTitle'    => 'Founder',
        'image'       => 'https://hiremusicians.com/wp-content/uploads/2026/08/headshot-large.jpg',
        'worksFor'    => ['@id' => home_url()],
        'description' => 'John Filippone is a musician and software engineer from Austin, Texas, and the founder of HireMusicians.com.',
    ],
];

$schema = clean_schema_array($schema);

?>
<script type="application/ld+json">
<?php echo json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
