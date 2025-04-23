<?php
$collections = get_collections();

foreach ($collections as $collection) {
    echo get_template_part('template-parts/account/collection-listing', '', [
        'post_id'       => $collection['post_id'],
        'name'          => 'Favorites',
        'thumbnail_url' => $thumbnail_url ? $thumbnail_url : get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp',
        'num_listings'  => count($collection['listings']),
        'edit_url'      => $collection['post_id'] == 'favorites' ? '/favorites' : '/collections/' . $collection['post_id'],
        'allow_delete'  => $collection['post_id'] != 'favorites',
    ]);
}

exit;

// Show Favorites collection

// Get thumbnail for favorites
$thumbnail_url = null;
echo get_template_part('template-parts/account/collection-listing', '', [
    'post_id' => '',
    'name' => 'Favorites',
    'thumbnail_url' => $thumbnail_url ? $thumbnail_url : get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp',
    'num_listings' => 1,
    'allow_delete' => false,
]);


// Show User Collections
$current_user_id = get_current_user_id();
$collections = get_user_meta($current_user_id, 'collections', true);
if ( $collections and count($collections) > 0 ) {

    // Query the posts
    $args = [
        'post_type'      => 'collection',
        'post__in'       => $collections,
        'post_status'    => 'publish',
        'orderby'        => 'post__in',
        'posts_per_page' => -1
    ];
    $query = new WP_Query($args);
    if ($query->have_posts()) { ?>

        <!-- Display user's collections -->

        <?php while ($query->have_posts()) {
            $query->the_post();
            $thumbnail_url = null; //get_the_post_thumbnail_url(get_the_ID(), 'standard-listing');
            echo get_template_part('template-parts/account/collection-listing', '', [
                'post_id' => get_the_ID(),
                'name' => get_post_meta(get_the_ID(), 'name', true),
                'thumbnail_url' => $thumbnail_url ? $thumbnail_url : get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp',
                'num_listings' => 0,
                'allow_delete' => true,
            ]);
        }
    }
}
