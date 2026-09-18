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

// Hero section - search live musicians with location auto filled; future will have 3 categories dropdown (live, prod, education)
get_template_part('template-parts/landing-pages/hero-search-live-music', '', []);
// Call to sign up
// How it works section
// Musician Earnings Database
// About Hire Musicians - Target broad keywords, value prop
// Links - verticals, categories, locations

get_footer();
