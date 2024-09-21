<?php


function register_post_types() {
    // Venue
    register_post_type('venue', array(
        //'rewrite' => array('slug', 'venues'),
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
}

add_action('init', 'register_post_types');

/*
function jm_register_my_custom_post_type() : void {
	$labels = [
		'singular_name' => _x( 'Venue', 'Post Type Singular Name', 'jm' ),
		'menu_name' => __( 'Venues', 'jm' ),
		'name_admin_bar' => __( 'Venues', 'jm' ),
		'archives' => __( 'Venues Archives', 'jm' ),
		'attributes' => __( 'Venues Attributes', 'jm' ),
		'parent_item_colon' => __( 'Parent Venue:', 'jm' ),
		'all_items' => __( 'All Venues', 'jm' ),
		'add_new_item' => __( 'Add New Venue', 'jm' ),
		'add_new' => __( 'Add New', 'jm' ),
		'new_item' => __( 'New Venue', 'jm' ),
		'edit_item' => __( 'Edit Venue', 'jm' ),
		'update_item' => __( 'Update Venue', 'jm' ),
		'view_item' => __( 'View Venue', 'jm' ),
		'view_items' => __( 'View Venues', 'jm' ),
		'search_items' => __( 'Search Venues', 'jm' ),
		'not_found' => __( 'Venue Not Found', 'jm' ),
		'not_found_in_trash' => __( 'Venue Not Found in Trash', 'jm' ),
		'featured_image' => __( 'Featured Image', 'jm' ),
		'set_featured_image' => __( 'Set Featured Image', 'jm' ),
		'remove_featured_image' => __( 'Remove Featured Image', 'jm' ),
		'use_featured_image' => __( 'Use as Featured Image', 'jm' ),
		'insert_into_item' => __( 'Insert into Venue', 'jm' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Venue', 'jm' ),
		'items_list' => __( 'Venues List', 'jm' ),
		'items_list_navigation' => __( 'Venues List Navigation', 'jm' ),
		'filter_items_list' => __( 'Filter Venues List', 'jm' ),
	];
	$labels = apply_filters( 'venue-labels', $labels );

	$args = [
		'label' => __( 'Venue', 'jm' ),
		'description' => __( 'Venues', 'jm' ),
		'labels' => $labels,
		'supports' => [
			'title',
			'editor',
			'thumbnail',
		],
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'menu_icon' => 'dashicons-admin-post',
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'exclude_from_search' => false,
		'has_archive' => 'venues',
		'can_export' => true,
		'capability_type' => 'page',
		'show_in_rest' => true,
	];
*/
?>


