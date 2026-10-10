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

$meta_title = 'Hire Live Musicians Near Austin, Texas';
$meta_description = 'Hire live musicians for weddings, parties, corporate events, and more. All it takes is a free account to send inquiries and get responses from musicians.';
add_filter( 'pre_get_document_title', function ( $title ) use ( $meta_title ) {
        return esc_html( $meta_title );
});
add_action( 'wp_head', function () use ( $meta_description ) {
    echo '<meta name="description" content="' . esc_attr( $meta_description ) . '">' . "\n";
}, 1);

// Get user collections
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');

get_header();

echo get_template_part('template-parts/landing-pages/hero-section-basic', '', [
    'breadcrumb_itmes' => [],
    'heading'          => "Find Live Musicians in Austin, Texas",
    'description'      => "Elevate your next event when you hire live musicians in Austin, Texas. From lively corporate events and elegant weddings to private parties, bring the authentic sound of the Live Music Capital of the World directly to your guests.",
]);
echo get_template_part('template-parts/search/search-page', '', [
    'send_first_page'      => false,
    'hide_location_filter' => true,
    'collections_map'      => $collections_map,
    'qcategory'            => isset($_GET['qcategory'])        ? $_GET['qcategory']        : '',
    'qgenre'               => isset($_GET['qgenre'])           ? $_GET['qgenre']           : '',
    'qsubgenre'            => isset($_GET['qsubgenre'])        ? $_GET['qsubgenre']        : '',
    'qinstrumentation'     => isset($_GET['qinstrumentation']) ? $_GET['qinstrumentation'] : '',
    'qsetting'             => isset($_GET['qsetting'])         ? $_GET['qsetting']         : '',
    'location_label'       => 'Austin, Texas',
    'lat'                  => 30.2671500,
    'lng'                  => -97.7430600,
]);
//get_template_part('template-parts/landing-pages/hero-search-live-music', '', []);
get_template_part('template-parts/landing-pages/categories-in-locale-links', '', [
    'heading'     => "Explore categories in Austin, Texas",
    'locale_slug' => 'austin',
]);
get_template_part('template-parts/landing-pages/simple-feature-section', '', [
    'heading'     => "Tips for Getting Accurate Quotes from Musicians",
    'sub_heading' => "Providing clear, comprehensive details upfront ensures you receive fast, precise quotes without hidden fees down the line. When requesting pricing for your event, include these key factors:",
    'features'    => [
        [ 'name' => "Ensemble Size & Format", 'description' => "State whether you need a solo acoustic performer, duo, trio, or full party band. The number of musicians on stage directly drives base rates, travel costs, and sound setup needs." ],
        [ 'name' => "Event Timeline & Schedule", 'description' => "Outline the complete schedule, including target arrival and load-in times, soundcheck, guest arrival, performance start/end, and scheduled breaks." ],
        [ 'name' => "Specific Location & Venue Setup", 'description' => "Provide the venue name, neighborhood, and whether the performance space is indoors or outdoors. Mention logistics like stairs, elevator access, or distance from parking to the stage." ],
        [ 'name' => "Duration of Performance", 'description' => "Clarify total time on-site versus actual playing time (for example, two 45-minute sets across a 2-hour window). Note if you need background music run through their sound system during band breaks." ],
        [ 'name' => "Equipment Needed vs. Provided", 'description' => "Specify whether the venue provides sound, microphones, staging, and power access, or if the musicians need to bring a complete PA system, backline, and stage lighting." ],
        [ 'name' => "Special Rehearsal or Song Requests", 'description' => "Note if you require specific custom songs (like wedding ceremony processionals or key corporate introductions), as these may factor into preparation time." ],
    ],
]);
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
get_template_part('template-parts/landing-pages/locale-category-links', '', [
    'heading' => 'Discover Musicians by Category',
]);

get_footer();
