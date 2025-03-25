<?php


function register_post_types() {
    // Venue
    register_post_type('venue', array(
        'rewrite' => array('slug' => 'venues'),
		//'show_in_admin_bar' => true,
		//'show_in_nav_menus' => true,
		//'exclude_from_search' => false,
		//'show_ui' => true,
		//'show_in_menu' => true,
		//'can_export' => true,
		//'capability_type' => 'page',
		'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'supports' => array('title'),
        'labels' => array(
          'name' => 'Venue',
          'add_new_item' => 'Add New Venue',
          'edit_item' => 'Edit Venue',
          'all_items' => 'All Venues',
          'singular_name' => 'Venue'
        ),
        'menu_icon' => 'dashicons-store'
    ));

    // Venue Review
    register_post_type('venue_review', array(
        'public' => true,
        'show_in_rest' => true,
        'supports' => array('title'),
        'labels' => array(
          'name' => 'Venue Review',
          'add_new_item' => 'Add New Venue Review',
          'edit_item' => 'Edit Venue Review',
          'all_items' => 'All Venue Reviews',
          'singular_name' => 'Venue Review'
        ),
        'menu_icon' => 'dashicons-star-empty'
    ));

    // Venue Review Submission
    register_post_type('review_submission', array(
        'public' => true,
        'show_in_rest' => true,
        'supports' => array('title'),
        'labels' => array(
          'name' => 'Review Submission',
          'add_new_item' => 'Add New Review Submission',
          'edit_item' => 'Edit Review Submission',
          'all_items' => 'All Review Submissions',
          'singular_name' => 'Review Submission'
        ),
        'menu_icon' => 'dashicons-clipboard'
    ));

    // Listing
    register_post_type('listing', array(
        'public' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'thumbnail'),
        'labels' => array(
          'name' => 'Listing',
          'add_new_item' => 'Add New Listing',
          'edit_item' => 'Edit Listing',
          'all_items' => 'All Listings',
          'singular_name' => 'Listing'
        ),
        'menu_icon' => 'dashicons-id-alt'
    ));

    // Collections
    register_post_type('collection', array(
        'public' => true,
        'show_in_rest' => true,
        'supports' => array('title'),
        'labels' => array(
          'name' => 'Collection',
          'add_new_item' => 'Add New Collection',
          'edit_item' => 'Edit Collection',
          'all_items' => 'All Collections',
          'singular_name' => 'Collection'
        ),
        'menu_icon' => 'dashicons-list-view'
    ));

    // Performance
    register_post_type('performance', array(
        'public' => true,
        'show_in_rest' => true,
        'supports' => array('title'),
        'labels' => array(
          'name' => 'Performance',
          'add_new_item' => 'Add New Performance',
          'edit_item' => 'Edit Performance',
          'all_items' => 'All Performances',
          'singular_name' => 'Performance'
        ),
        'menu_icon' => 'dashicons-microphone'
    ));

    // Artists
    register_post_type('artist', array(
        'public' => true,
        'show_in_rest' => true,
        'supports' => array('title'),
        'labels' => array(
          'name' => 'Artist',
          'add_new_item' => 'Add New Artist',
          'edit_item' => 'Edit Artist',
          'all_items' => 'All Artists',
          'singular_name' => 'Artist'
        ),
        'menu_icon' => 'dashicons-art'
    ));
}

function register_taxonomies() {
  register_taxonomy('mcategory', array('listing', 'artist'), array(
    'public' => true,
    'hierarchical' => false,
    'show_in_rest' => true,
    'labels' => array(
      'name' => 'Category',
      'add_new_item' => 'Add New Category',
      'edit_item' => 'Edit Category',
      'all_items' => 'All Categories',
      'singular_name' => 'Category'
    )
  ));
  register_taxonomy('genre', array('listing', 'artist'), array(
    'public' => true,
    'hierarchical' => false,
    'show_in_rest' => true,
    'labels' => array(
      'name' => 'Genres',
      'add_new_item' => 'Add New Genre',
      'edit_item' => 'Edit Genre',
      'all_items' => 'All Genres',
      'singular_name' => 'Genre'
    )
  ));
  register_taxonomy('subgenre', array('listing', 'artist'), array(
    'public' => true,
    'hierarchical' => false,
    'show_in_rest' => true,
    'labels' => array(
      'name' => 'Sub Genres',
      'add_new_item' => 'Add New Sub Genre',
      'edit_item' => 'Edit Sub Genre',
      'all_items' => 'All Sub Genres',
      'singular_name' => 'Sub Genre'
    )
  ));
  register_taxonomy('instrumentation', array('listing', 'artist'), array(
    'public' => true,
    'hierarchical' => false,
    'show_in_rest' => true,
    'labels' => array(
      'name' => 'Instrumentations',
      'add_new_item' => 'Add New Instrumentation',
      'edit_item' => 'Edit Instrumentation',
      'all_items' => 'All Instrumentations',
      'singular_name' => 'Instrumentation'
    )
  ));
  register_taxonomy('setting', array('listing', 'artist'), array(
    'public' => true,
    'hierarchical' => false,
    'show_in_rest' => true,
    'labels' => array(
      'name' => 'Settings',
      'add_new_item' => 'Add New Setting',
      'edit_item' => 'Edit Setting',
      'all_items' => 'All Settings',
      'singular_name' => 'Setting'
    )
  ));
  register_taxonomy('tag', array('listing', 'artist'), array(
    'public' => true,
    'hierarchical' => false,
    'show_in_rest' => true,
    'labels' => array(
      'name' => 'Tags',
      'add_new_item' => 'Add New Tag',
      'edit_item' => 'Edit Tag',
      'all_items' => 'All Tags',
      'singular_name' => 'Tag'
    )
  ));
}

add_action('init', 'register_post_types');
add_action('init', 'register_taxonomies');

function get_taxonomy_terms() {
    $taxonomy = $_GET['taxonomy'];
    return get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false
    ));
}
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'taxonomies/terms', [
        'methods' => 'GET',
        'callback' => 'get_taxonomy_terms',
    ]);
});
