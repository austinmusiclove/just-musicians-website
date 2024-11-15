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
        'menu_icon' => 'dashicons-feedback'
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

    // Performance
    register_post_type('listing', array(
        'public' => true,
        'show_in_rest' => true,
        'supports' => array('title'),
        'labels' => array(
          'name' => 'Listing',
          'add_new_item' => 'Add New Listing',
          'edit_item' => 'Edit Listing',
          'all_items' => 'All Listings',
          'singular_name' => 'Listing'
        ),
        'menu_icon' => 'dashicons-id-alt'
    ));
}

add_action('init', 'register_post_types');
