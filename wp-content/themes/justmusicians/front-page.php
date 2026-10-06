<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package JustMuscians
 */


get_header();

get_template_part('template-parts/landing-pages/hero-search-live-music', '', []);
get_template_part('template-parts/landing-pages/how-it-works', '', [
    'heading'       => 'Hire Musicians For An Event',
    'description' => 'Hire live musicians for weddings, parties, corporate events, and more. All it takes is a free account to send inquiries and get responses.',
    'image'       => get_template_directory_uri() . '/lib/images/other/inquiry-form-location.png',
    'steps'       => [
        'Tell us about your event',
        'Send your inquiry directly to musicians you want to hire',
        'Get responses and price quotes',
        'Book directly with musicians',
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
// Links - verticals, categories, locations

get_footer();
