<?php
/**
 * The template for displaying the listing form
 *
 * @package JustMusicians
 */

get_header();

$genres            = get_the_terms(get_the_ID(), 'genre');
$subgenres         = get_the_terms(get_the_ID(), 'subgenre');
$instrumentations  = get_the_terms(get_the_ID(), 'instrumentation');
$settings          = get_the_terms(get_the_ID(), 'setting');
$keywords          = get_the_terms(get_the_ID(), 'keyword');

$venues_combined  = [];
$verified_ids     = get_field('venues_played_verified') ?: [];
$unverified_ids   = get_field('venues_played_unverified') ?: [];
$verified_posts   = array_map('get_post', $verified_ids);
$unverified_posts = array_map('get_post', $unverified_ids);
foreach (array_merge($verified_posts, $unverified_posts) as $venue) {
    if ($venue && $venue->post_type === 'venue') {
        $venues_combined[] = [
            'name'             => get_field('name', $venue->ID),
            'street_address'   => get_field('street_address', $venue->ID),
            'address_locality' => get_field('address_locality', $venue->ID),
            'postal_code'      => get_field('postal_code', $venue->ID),
            'address_region'   => get_field('address_region', $venue->ID),
        ];
    }
}

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
   'genres'            => $genres,
   'subgenres'         => $subgenres,
   'instrumentations'  => $instrumentations,
   'settings'          => $settings,
   'keywords'          => $keywords,
   'venues_combined'   => $venues_combined,
   'youtube_video_ids' => $youtube_video_ids,
));

get_footer();
