<?php

$vertical = get_query_var('vertical');

$breadcrumb_items = [
    [ 'label' => 'Home', 'url' => home_url('/') ],
    [ 'label' => get_label_from_slug($vertical) ],
];

get_header();

get_template_part('template-parts/landing-pages/hero-section-cta', '', [
    'heading'          => 'Ready to Hire Live Musicians?',
    'description'      => 'Browse live musicians in your area. Filter by genre, ensemble size, instrumentation and more.',
    'cta_text'         => 'Search Musicians',
    'cta_url'          => site_url('/live-music/search/'),
    'breadcrumb_items' => [
        [
            'label' => 'Home',
            'url'   => home_url(),
        ],
        [
            'label' => 'Live Music',
            'url'   => site_url('/live-music/'),
        ],
    ],
]);
get_template_part('template-parts/landing-pages/benefits-cta', '', [
    'heading'       => 'Are You a Musician? Let the Gigs Come to You.',
    'description' => 'Stop chasing leads and start playing more shows. Build a free musician listing to connect with event planners, venues, and private clients looking to hire musicians for their next event.',
    'image'       => get_template_directory_uri() . '/lib/images/other/create-listing.png',
    'benefits'    => [
        'Get discovered on Hire Musicians',
        'Zero membership fees',
        'Zero commission on leads',
        'Unlimited booking requests',
        'Build trust with client reviews',
    ],
    'cta_text'          => 'Sign Up as a Musician',
    'cta_url'           => site_url('/listing-form/'),
    'sign_up_to_access' => 'Sign up to create your listing',
]);

get_template_part('template-parts/landing-pages/simple-cta', '', [
    'heading'  => 'Find Live Musicians by Location',
    'cta_text' => 'Locations',
    'cta_url'  => site_url('/live-music/locations/'),
]);

get_footer();
