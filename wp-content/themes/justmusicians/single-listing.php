<?php
/**
 * The template for displaying listing pages
 *
 * @package JustMusicians
 */

get_header();

// Get user collections
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');

// Get user inquiries
$inquiries_result = get_user_inquiries([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$inquiries_map = array_column($inquiries_result['inquiries'], null, 'post_id');

$categories        = wp_list_pluck(get_the_terms(get_the_ID(), 'mcategory')       ?: [], 'name');
$genres            = wp_list_pluck(get_the_terms(get_the_ID(), 'genre')           ?: [], 'name');
$subgenres         = wp_list_pluck(get_the_terms(get_the_ID(), 'subgenre')        ?: [], 'name');
$instrumentations  = wp_list_pluck(get_the_terms(get_the_ID(), 'instrumentation') ?: [], 'name');
$settings          = wp_list_pluck(get_the_terms(get_the_ID(), 'setting')         ?: [], 'name');
$keywords          = wp_list_pluck(get_the_terms(get_the_ID(), 'keyword')         ?: [], 'name');

$youtube_video_post_ids = get_field('youtube_videos');
$youtube_video_data     = get_youtube_video_data($youtube_video_post_ids);

$venues_combined      = [];
$verified_venue_ids   = get_field('venues_played_verified', false, false) ?: [];
$unverified_venue_ids = get_field('venues_played_unverified', false, false) ?: [];
foreach (array_unique(array_merge($verified_venue_ids, $unverified_venue_ids)) as $venue_id) {
    $venues_combined[] = [
        'ID'               => $venue_id,
        'name'             => get_field('name',             $venue_id),
        'street_address'   => get_field('street_address',   $venue_id),
        'address_locality' => get_field('address_locality', $venue_id),
        'postal_code'      => get_field('postal_code',      $venue_id),
        'address_region'   => get_field('address_region',   $venue_id),
];}

// Hero
echo get_template_part('template-parts/listing-page/hero', '', [
   'instance'        => 'listing-page',
   'genres'          => $genres,
   'collections_map' => $collections_map,
   'rating'          => get_field('rating') ?? 0,
   'review_count'    => get_field('review_count') ?? 0,
]);

// Content
echo get_template_part('template-parts/listing-page/content', '', [
   'instance'           => 'listing-page',
   'categories'         => $categories,
   'genres'             => $genres,
   'subgenres'          => $subgenres,
   'instrumentations'   => $instrumentations,
   'settings'           => $settings,
   'keywords'           => $keywords,
   'venues_combined'    => $venues_combined,
   'youtube_video_data' => $youtube_video_data,
   'inquiries_map'      => $inquiries_map,
]);

echo get_template_part('template-parts/schema/local-business-schema', '', [
    'name'        => get_field('name'),
    'description' => get_field('description'),
    'website'     => get_field('website'),
    'phone'       => get_field('phone'),
    'genres'      => $genres,
    'url'         => get_permalink(),
    'image'       => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'),
    'city'        => get_field('city'),
    'state'       => get_field('state'),
]);

// Show review modal popup on page load when mdl=review in url
if (!empty($_GET['mdl']) and $_GET['mdl'] == 'review') {
    if (is_user_logged_in()) { ?>
        <span x-init="_openReviewModal('', '')"></span>
    <?php } else { ?>
        <span x-init="showSignupModal = true; signupModalMessage = 'Sign up to write a review'; loginModalMessage = 'Sign in to write a review';"></span>
    <?php }
}

get_footer();
