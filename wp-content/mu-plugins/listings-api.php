<?php
/**
 * Plugin Name: Just Musicians Listings API
 * Description: A custom plugin to expose REST APIs for managing listing posts
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Include
require_once 'listings-api/get-listing.php';
require_once 'listings-api/get-listings.php';
require_once 'listings-api/post-listing.php';
require_once 'listings-api/update-listing.php';

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route( 'v1', 'listings', [
        'methods' => 'GET',
        'callback' => 'get_listings_request_handler',
    ]);
    register_rest_route( 'v1', 'listings', [
        'methods' => 'POST',
        'callback' => function($request) {
            $args = get_listing_args();
            if (!empty($args['post_id'])) {
                return update_listing($args);
            } else {
                return post_listing($args);
            }
        },
        'permission_callback' => function() {
            // User must be logged in
            if (!is_user_logged_in()) {
                return false;
            }

            // Admin can create or edit any post
            if (current_user_can('administrator')) {
                return true;
            }

            // Any user can create a listing
            if (empty($_POST['post_id'])) {
                return true;
            }

            // User can only edit their own listing
            if (!empty($_POST['post_id'])) {
                $user_listings = get_user_meta(get_current_user_id(), 'listings', true);
                return in_array($_POST['post_id'], $user_listings);
            }
        }
    ]);
});

function get_listings_request_handler($request) {
    return get_listings([
        'search' => $request['s'],
        'types' => $request['types'],
        'categories' => $request['categories'],
        'genres' => $request['genres'],
        'subgenres' => $request['subgenres'],
        'instrumentation' => $request['instrumentation'],
        'settings' => $request['settings'],
        'tags' => $request['tags'],
        'verified' => $request['verified'],
        'page' => $request['page'],
    ]);
}

function get_listing_args() {
    $categories = (isset($_POST['categories'])) ? array_filter(array_map('sanitize_text_field', $_POST['categories'])) : [];
    $genres = (isset($_POST['genres'])) ? array_filter(array_map('sanitize_text_field', $_POST['genres'])) : [];
    $subgenres = (isset($_POST['subgenres'])) ? array_filter(array_map('sanitize_text_field', $_POST['subgenres'])) : [];
    $instrumentations = (isset($_POST['instrumentations'])) ? array_filter(array_map('sanitize_text_field', $_POST['instrumentations'])) : [];
    $settings = (isset($_POST['settings'])) ? array_filter(array_map('sanitize_text_field', $_POST['settings'])) : [];
    return [
        'post_id'          => !empty($_POST['post_id'])            ? $_POST['post_id']            : null,
        'name'             => !empty($_POST['performer_name'])     ? $_POST['performer_name']     : null,
        'description'      => !empty($_POST['description'])        ? $_POST['description']        : null,
        'city'             => !empty($_POST['city'])               ? $_POST['city']               : null,
        'state'            => !empty($_POST['state'])              ? $_POST['state']              : null,
        'zip_code'         => !empty($_POST['zip_code'])           ? $_POST['zip_code']           : null,
        'bio'              => !empty($_POST['bio'])                ? $_POST['bio']                : null,
        'categories'       => !empty($categories)                  ? $categories                  : null,
        'genres'           => !empty($genres)                      ? $genres                      : null,
        'subgenres'        => !empty($subgenres)                   ? $subgenres                   : null,
        'instrumentations' => !empty($instrumentations)            ? $instrumentations            : null,
        'settings'         => !empty($settings)                    ? $settings                    : null,
        'thumbnail_file'   => !empty($_FILES['cropped-thumbnail']) ? $_FILES['cropped-thumbnail'] : null,
    ];
}
