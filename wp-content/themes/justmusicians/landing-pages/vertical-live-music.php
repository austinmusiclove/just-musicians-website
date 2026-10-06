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
// Musician Earnings Database
get_template_part('template-parts/landing-pages/benefits-cta', '', [
    'title'       => 'Are You a Musician? Set up Your Account for Free',
    'description' => 'Create a free musician listing to show up in searches and start getting inquiries from qualified buyers.',
    'image'       => get_template_directory_uri() . '/lib/images/other/create-listing.png',
    'benefits'    => [
        'Show up in searches on Hire Musicians',
        'No membership fees',
        'No lead fees',
        'Unlimited inquiries',
        'Collect reviews',
    ],
    'cta_text'          => 'Sign Up as a Musician',
    'cta_url'           => site_url('/listing-form/'),
    'sign_up_to_access' => 'Sign up to create your listing',
]);

get_footer();
