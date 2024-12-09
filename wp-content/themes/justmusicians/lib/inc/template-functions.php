<?php

// Move ACF json Folder
add_filter('acf/settings/load_json', 'my_acf_json_load_point');
function my_acf_json_load_point( $paths ) {
  // remove original path (optional)
  unset($paths[0]);
  // append path
  $paths[] = get_stylesheet_directory() . '/lib/acf-json';
  // return
  return $paths;
}
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
function my_acf_json_save_point( $path ) {
  // update path
  $path = get_stylesheet_directory() . '/lib/acf-json';
  // return path
  return $path;
}

/**
 * Ensure all image blocks have an alignment class.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 * @return string Modified block content.
 */
function tomjn_add_align_class( $block_content, $block ) {
    $alignment = 'none';
    if ( ! empty( $block['attrs']['align'] ) ) {
        $alignment = $block['attrs']['align'];
    }
    $content = str_replace(
        'class="wp-block-image',
        'data-align="' . esc_attr( $alignment ) .'" class="wp-block-image',
        $block_content
    );
    return $content;
}
add_filter( 'render_block_core/image', 'tomjn_add_align_class', 10, 2 );

// Remove breadcrumb schema
add_filter( 'wpseo_schema_graph_pieces', 'remove_breadcrumbs_from_schema', 11, 2 );
add_filter( 'wpseo_schema_webpage', 'remove_breadcrumbs_property_from_webpage', 11, 1 );

/**
 * Removes the breadcrumb graph pieces from the schema collector.
 *
 * @param array  $pieces  The current graph pieces.
 * @param string $context The current context.
 *
 * @return array The remaining graph pieces.
 */
function remove_breadcrumbs_from_schema( $pieces, $context ) {
    return \array_filter( $pieces, function( $piece ) {
        return ! $piece instanceof \Yoast\WP\SEO\Generators\Schema\Breadcrumb;
    } );
}

/**
 * Removes the breadcrumb property from the WebPage piece.
 *
 * @param array $data The WebPage's properties.
 *
 * @return array The modified WebPage properties.
 */
function remove_breadcrumbs_property_from_webpage( $data ) {
    if (array_key_exists('breadcrumb', $data)) {
        unset($data['breadcrumb']);
    }
    return $data;
}

// Set search results to noindex, follow
add_filter( 'wpseo_robots', 'yoast_seo_robots_modify_search' );

function yoast_seo_robots_modify_search( $robots ) {
  if ( is_search() ) {
    return "noindex, follow";
  } else {
    return $robots;
  }
}


// Hide drafts and private posts from ACF post object field
add_filter('acf/fields/post_object/query', 'br_acf_fields_post_object_query', 10, 3);
function br_acf_fields_post_object_query( $args, $field, $post_id ) {

    $args['post_status'] = 'publish';

    return $args;
}

function br_get_featured_image(int $image_id, string $size = 'medium', $opts = []) {
	$opts['srcset'] = wp_get_attachment_image_srcset( $image_id, $size );
	$opts['sizes'] = wp_calculate_image_sizes($size, null, null, $image_id);
	
	return wp_get_attachment_image($image_id, $size, false, $opts);
}


