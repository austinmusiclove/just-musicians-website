<?php

/**
 *
 * @link              austinmusiclove.com
 * @since             1.0.0
 * @package           Meta Post API
 *
 * @wordpress-plugin
 * Plugin Name:       Meta Post API
 * Plugin URI:        austinmusiclove.com
 * Description:       Exposes an API that allows you to create posts with meta data
 * Version:           1.0.0
 * Author:            Austin Music Love
 * Author URI:        austinmusiclove.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       meta-post
 * Domain Path:       /languages
 */


// Wrapper for wp_insert_post
// https://developer.wordpress.org/reference/functions/wp_insert_post/
//
// does not support post_content_filtered or post_excerpt yet
// TODO: check auth
function insert_post( WP_REST_Request $request ) {
  $post_arr = array(
    'ID' => $request['ID'],
    'post_author' => $request['post_author'],
    'post_date' => $request['post_date'],
    'post_date_gmt' => $request['post_date_gmt'],
    'post_content' => $request['post_content'],
    //'post_content_filtered' => $request['post_content_filtered'],
    'post_title' => $request['post_title'],
    //'post_excerpt' => $request['post_excerpt'],
    'post_status' => $request['post_status'],
    'post_type' => $request['post_type'],
    'comment_status' => $request['comment_status'],
    'ping_status' => $request['ping_status'],
    'post_password' => $request['post_password'],
    'post_name' => $request['post_name'],
    'to_ping' => $request['to_ping'],
    'pinged' => $request['pinged'],
    'post_modified' => $request['post_modified'],
    'post_modified_gmt' => $request['post_modified_gmt'],
    'post_parent' => $request['post_parent'],
    'menu_order' => $request['menu_order'],
    'post_mime_type' => $request['post_mime_type'],
    'guid' => $request['guid'],
    'import_id' => $request['import_id'],
    'post_category' => $request['post_category'],
    'tags_input' => $request['tags_input'],
    'tax_input' => $request['tax_input'],
    'meta_input' => $request['meta_input'],
  );
  $post_id = wp_insert_post( $post_arr, true );

  if( is_wp_error( $post_id ) ) {
    return $post_id->get_error_message();
  }

  if (!isset($request['main_image_url'])) {
    return array('post_id' => $post_id);
  } else {
    $image_url        = $request['main_image_url']; // Define the image URL here
    $image_name       = $post_id . '-main-image.png';
    $upload_dir       = wp_upload_dir(); // Set upload folder
    $image_data       = file_get_contents($image_url); // Get image data
    $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
    $filename         = basename( $unique_file_name ); // Create image file name

    // Check folder permission and define file location
    if( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

    // Create the image  file on the server
    file_put_contents( $file, $image_data );

    // Check image file type
    $wp_filetype = wp_check_filetype( $filename, null );

    // Set attachment data
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name( $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    // Create the attachment
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    if( is_wp_error( $attach_id ) ) {
      return $attach_id->get_error_message();
    }

    // Include image.php
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Define attachment metadata
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

    // Assign metadata to attachment
    wp_update_attachment_metadata( $attach_id, $attach_data );

    // And finally assign featured image to post
    set_post_thumbnail( $post_id, $attach_id );

    return array(
      'post_id' => $post_id,
      'attachment_id' => $attach_id,
      'attachment_url' => $upload_dir['url'] . '/' . $filename,
      'update_image_body' => array(
        'ID' => $post_id,
        'meta_input' => array(
          '_gallery' => array(
            $attach_id => $upload_dir['url'] . '/' . $filename
          )
        )
      )
    );
  }
}

function update_post( WP_REST_Request $request ) {
  $post_arr = array(
    'ID' => $request['ID'],
    //'post_author' => $request['post_author'],
    //'post_date' => $request['post_date'],
    //'post_date_gmt' => $request['post_date_gmt'],
    //'post_content_filtered' => $request['post_content_filtered'],
    //'post_excerpt' => $request['post_excerpt'],
    'post_status' => 'publish',
    //'post_type' => $request['post_type'],
    //'comment_status' => $request['comment_status'],
    //'ping_status' => $request['ping_status'],
    //'post_password' => $request['post_password'],
    //'post_name' => $request['post_name'],
    //'to_ping' => $request['to_ping'],
    //'pinged' => $request['pinged'],
    //'post_modified' => $request['post_modified'],
    //'post_modified_gmt' => $request['post_modified_gmt'],
    //'post_parent' => $request['post_parent'],
    //'menu_order' => $request['menu_order'],
    //'post_mime_type' => $request['post_mime_type'],
    //'guid' => $request['guid'],
    //'import_id' => $request['import_id'],
    //'post_category' => $request['post_category'],
    //'tags_input' => $request['tags_input'],
    'meta_input' => $request['meta_input'],
  );
  if (isset($request['post_content'])) { $post_arr['post_content'] = $request['post_content']; }
  if (isset($request['tax_input'])) { $post_arr['tax_input'] = $request['tax_input']; }
  if (isset($request['post_title'])) { $post_arr['post_title'] = $request['post_title']; }
  if (isset($request['post_status'])) { $post_arr['post_status'] = $request['post_status']; }

  $post_id = wp_update_post( wp_slash($post_arr), true );

  if( is_wp_error( $post_id ) ) {
    return $post_id->get_error_message();
  }

  return $post_id;
}

add_action('rest_api_init', function () {
  register_rest_route( 'insert-post/v1', 'posts', [
    'methods' => 'POST',
    'callback' => 'insert_post',
  ]);
});

add_action('rest_api_init', function () {
 register_rest_route( 'update-post/v1', 'posts', [
    'methods' => 'PUT',
    'callback' => 'update_post',
  ]);
});

