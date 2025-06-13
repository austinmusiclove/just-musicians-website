<?php
/**
 * The template for displaying the listing form
 *
 * @package JustMusicians
 */

get_header();

$categories        = wp_list_pluck(get_the_terms(get_the_ID(), 'mcategory')       ?: [], 'name');
$genres            = wp_list_pluck(get_the_terms(get_the_ID(), 'genre')           ?: [], 'name');
$subgenres         = wp_list_pluck(get_the_terms(get_the_ID(), 'subgenre')        ?: [], 'name');
$instrumentations  = wp_list_pluck(get_the_terms(get_the_ID(), 'instrumentation') ?: [], 'name');
$settings          = wp_list_pluck(get_the_terms(get_the_ID(), 'setting')         ?: [], 'name');
$keywords          = wp_list_pluck(get_the_terms(get_the_ID(), 'keyword')         ?: [], 'name');

$venues_combined  = [];
$verified_venue_ids     = get_field('venues_played_verified') ?: [];
$unverified_venue_ids   = get_field('venues_played_unverified') ?: [];
foreach (array_unique(array_merge($verified_venue_ids, $unverified_venue_ids)) as $venue_id) {
    $venues_combined[] = [
        'ID'               => $venue_id,
        'name'             => get_field('name',             $venue_id),
        'street_address'   => get_field('street_address',   $venue_id),
        'address_locality' => get_field('address_locality', $venue_id),
        'postal_code'      => get_field('postal_code',      $venue_id),
        'address_region'   => get_field('address_region',   $venue_id),
];}

$youtube_video_urls = get_field('youtube_video_urls');
$youtube_video_ids = get_youtube_video_ids($youtube_video_urls);



// Hero
echo get_template_part('template-parts/listing/hero', '', array(
   'instance' => 'listing-page',
   'genres'   => $genres,
));

// Content
echo get_template_part('template-parts/listing/content', '', array(
   'instance'          => 'listing-page',
   'categories'        => $categories,
   'genres'            => $genres,
   'subgenres'         => $subgenres,
   'instrumentations'  => $instrumentations,
   'settings'          => $settings,
   'keywords'          => $keywords,
   'venues_combined'   => $venues_combined,
   'youtube_video_ids' => $youtube_video_ids,
));

get_footer();
