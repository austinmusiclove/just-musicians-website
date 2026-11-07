<?php

// Post data

// Set meta title and description
$meta_title = "Join Hire Musicians | Free Live Music Booking Platform for Artists";
$meta_description = "Stop losing gig money to platform fees. Join Hire Musicians to showcase your music, accept gig requests, and deal directly with buyers for free.";
add_filter( 'pre_get_document_title', function ( $title ) use ( $meta_title ) {
        return esc_html( $meta_title );
});
add_action( 'wp_head', function () use ( $meta_description ) {
    echo '<meta name="description" content="' . esc_attr( $meta_description ) . '">' . "\n";
}, 1);


// Breadcrumb
// Future state will be Home / Live Music in Locale / Category
$breadcrumb_items = [
    [ 'label' => 'Home', 'url' => home_url('/') ],
    [ 'label' => 'Join Hire Musicians' ],
];

// Generate page content
get_header( null, [ 'signup_redirect' => home_url('/listings/'), ]);

?><main role="main"><?php

get_template_part('template-parts/landing-pages/hero-section-basic', '', [
    'breadcrumb_items' => $breadcrumb_items,
    'heading'          => "Join Hire Musicians",
    'description'      => "Are you a musician looking to book paid gigs? Stop chasing leads and start playing more shows! Connect directly with venues, event planners, and private buyers with zero membership fees and zero booking commissions.",
]);

// If logged out show version of benefits with sign up to join; redirect to listing form after sign up
// If logged in show benefits cta and call to action to create a listing

if ( is_user_logged_in() ) {
    get_template_part('template-parts/landing-pages/benefits-cta', '', [
        'heading'     => 'Never Pay to Get Booked Again',
        'description' => 'Keep 100% of your earnings and 100% of your client contacts. With no subscription fees, no gig commissions, and no pay-to-win algorithms, Hire Musicians gives you all the tools you need to book shows on your terms.',
        'image'       => get_template_directory_uri() . '/lib/images/other/create-listing-opt.png',
        'benefits'    => [
            'Get discovered on Hire Musicians',
            'Zero membership fees',
            'Zero commission on bookings',
            'Unlimited booking requests',
            'Unlimited categories',
            'Build trust with client reviews',
        ],
        'cta_text'          => 'Create your listing',
        'cta_url'           => site_url('/listing-form/'),
    ]);
} else {
    get_template_part('template-parts/landing-pages/benefits-cta', '', [
        'heading'     => 'Never Pay to Get Booked Again',
        'description' => 'Keep 100% of your earnings and 100% of your client contacts. With no subscription fees, no gig commissions, and no pay-to-win algorithms, Hire Musicians gives you all the tools you need to book shows on your terms.',
        'image'       => get_template_directory_uri() . '/lib/images/other/create-listing-opt.png',
        'benefits'    => [
            'Get discovered on Hire Musicians',
            'Zero membership fees',
            'Zero commission on bookings',
            'Unlimited booking requests',
            'Unlimited categories',
            'Build trust with client reviews',
        ],
        'cta_text'          => 'Sign up to create your listing',
        'cta_url'           => site_url('/listing-form/'),
        'sign_up_to_access' => 'Sign up to create your listing',
    ]);
}

?>

</main><?php

get_footer();
