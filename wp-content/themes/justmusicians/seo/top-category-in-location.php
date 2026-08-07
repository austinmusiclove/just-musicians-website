<?php

$category = get_query_var('seo-category') ?? 0;
$location = get_query_var('seo-location') ?? 0;

// Parse location
$location_label = '';
$city_lat = null;
$city_lng = null;
$location_data = !empty($location) ? get_seo_location($location) : null;
if (!empty($location_data)) {
    $location_label = $location_data['city'] . ', ' . $location_data['state'];
    $city_lat = $location_data['lat'];
    $city_lng = $location_data['lng'];
} else {
    wp_redirect(site_url());
    exit;
}

// Set page title and category filter
$title = '';
$description = '';
$category_name = '';
$term = get_term_by('slug', $category, 'mcategory');
if ( $term and !is_wp_error( $term ) ) {
    $category_name = $term->name;
    $title       = get_seo_page_title( $category, $location_label );
    $description = get_seo_meta_description($category, $location_label);
} else {
    wp_redirect(site_url());
    exit;
}


// Get user collections and events
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');


// Generate page content
get_header( null, [
    'header_arg_location_label' => $location_label,
    'header_arg_lat'            => $city_lat,
    'header_arg_lng'            => $city_lng,
] );

echo get_template_part('template-parts/search/search-page', '', [
    'send_first_page'  => true,
    'title'            => $title,
    'description'      => $description,
    'collections_map'  => $collections_map,
    'qcategory'        => $category_name,
    'qgenre'           => '',
    'qsubgenre'        => '',
    'qinstrumentation' => '',
    'qsetting'         => '',
    'lat'              => $city_lat,
    'lng'              => $city_lng,
    'area_served'      => [
        'city'  => $location_data['city'],
        'state' => $location_data['state'],
        'lat'   => $city_lat,
        'lng'   => $city_lng,
    ],
]);

// You may also be interested in
$supported_categories = get_seo_categories_for_location($location);
$other_categories = array_filter($supported_categories, fn($supported_category) => $supported_category !== $category);

if (count($other_categories) > 0) { ?>

<div class="container flex justify-center py-32">
    <div class="flex flex-col items-center sm:max-w-[600px]">
        <h2 class="font-sun-motter text-center text-25 mb-4">Explore other categories in <?php echo esc_html($location_label); ?></h2>
        <div class="flex items-center justify-center gap-2 flex-wrap">
            <?php foreach ($other_categories as $other_category) {
                $other_name = get_seo_category_plural_name($other_category); ?>
                <a class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20 hover:bg-yellow-light inline-block"
                    href="<?php echo esc_url(site_url('/live-music/' . $other_category . '/' . $location . '/')); ?>">
                    <?php echo esc_html($other_name); ?>
                </a>
            <?php } ?>
        </div>
    </div>
</div>

<?php } ?>

<?php
get_footer();
