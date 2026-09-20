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
    'title'       => 'Hire Musicians For An Event',
    'description' => 'Hire live musicians for weddings, parties, corporate events, and more. All it takes is a free account to send inquiries and get responses.',
    'image'       => get_template_directory_uri() . '/lib/images/other/inquiry-form-location.png',
    'steps'       => [
        'Tell us about your event',
        'Send your inquiry directly to musicians you want to hire',
        'Get responses and price quotes',
        'Book directly with musicians',
    ],
]);
// Call to sign up
// Musician Earnings Database
// Links - verticals, categories, locations

get_footer();
