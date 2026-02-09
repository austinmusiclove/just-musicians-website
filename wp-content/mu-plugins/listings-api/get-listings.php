<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

function get_listings($args) {
    $results = [];
    $search_term            = (!empty($args['search']))            ? sanitize_text_field($args['search'])                             : null;
    $name_search_term       = (!empty($args['name_search']))       ? sanitize_text_field($args['name_search'])                        : null;
    $verified               = (!empty($args['verified']))          ? rest_sanitize_boolean($args['verified'])                         : null;
    $ensemble_size          = get_ensemble_size_values($args);
    $min_ensemble_size      = (!empty($args['min_ensemble_size'])) ? sanitize_text_field($args['min_ensemble_size'])                  : null;
    $max_ensemble_size      = (!empty($args['max_ensemble_size'])) ? sanitize_text_field($args['max_ensemble_size'])                  : null;
    $sanitized_page         = (!empty($args['page']))              ? sanitize_text_field($args['page'])                               : null;
    $types                  = (!empty($args['types']))             ? rest_sanitize_array($args['types'])                              : [];
    $valid_categories       = (!empty($args['categories']))        ? validate_tax_input($args['categories'], 'mcategory')             : [];
    $valid_genres           = (!empty($args['genres']))            ? validate_tax_input($args['genres'], 'genre')                     : [];
    $valid_subgenres        = (!empty($args['subgenres']))         ? validate_tax_input($args['subgenres'], 'subgenre')               : [];
    $valid_instrumentations = (!empty($args['instrumentations']))  ? validate_tax_input($args['instrumentations'], 'instrumentation') : [];
    $valid_settings         = (!empty($args['settings']))          ? validate_tax_input($args['settings'], 'setting')                 : [];
    $valid_types            = validate_listing_types($types);
    $media_tags             = [...$valid_categories, ...$valid_genres, ...$valid_subgenres, ...$valid_instrumentations, ...$valid_settings];
    $page                   = (is_numeric($sanitized_page) and (int)$sanitized_page) ? (int)$sanitized_page : 1;
    $next_page              = $page + 1;

    $query_args = [
        'post_type'      => 'listing',
        'post_status'    => 'publish',
        'paged'          => $page,
        'posts_per_page' => 10,
        'orderby'        => [ 'meta_value_num' => 'DEC', 'ID' => 'ASC' ],
        'meta_key'       => 'rank',
    ];
    if (!empty($search_term)) {
        $query_args['s'] = $args['search'];
        $query_args['orderby'] = 'relevance';
    } else {
        //error_log('user listing search algo');
        //$query_args['use_listings_search_algo'] = true;
    }

    // Exclude listings
    if (!empty($args['exclude'])) {
        $query_args['post__not_in'] = $args['exclude'];
    }

    $meta_queries = [];
    $meta_queries[] = [ 'key' => '_thumbnail_id', 'compare' => 'EXISTS' ];
    $meta_queries[] = [ 'key' => 'name', 'value' => '', 'compare' => '!=' ];
    $meta_queries[] = [ 'key' => 'description', 'value' => '', 'compare' => '!=' ];
    $meta_queries[] = [ 'key' => 'city', 'value' => '', 'compare' => '!=' ];
    $meta_queries[] = [ 'key' => 'state', 'value' => '', 'compare' => '!=' ];
    if (!empty($name_search_term)) {
        $meta_queries[] = [
            'key' => 'name',
            'value' => $name_search_term,
            'compare' => 'LIKE',
        ];
    }
    if (!empty($types)) {
        $meta_queries[] = [
            'key' => 'type',
            'value' => $valid_types,
            'compare' => 'IN',
        ];
    }
    if (!empty($verified)) {
        $meta_queries[] = [
            'key' => 'verified',
            'value' => $verified,
        ];
    }
    // Ensemble Size
    if ((($min_ensemble_size or $max_ensemble_size) and ($min_ensemble_size != 1 or $max_ensemble_size != 10)) or !empty($ensemble_size)) {
        $ensemble_size_values = [];
        foreach ($ensemble_size as $option) {
            $ensemble_size_values[] = (string)$option;
        }
        $ensemble_size_query = [ 'relation' => 'OR' ];
        foreach ($ensemble_size_values as $value) {
            $ensemble_size_query[] = [
                'key'     => 'ensemble_size',
                'value'   => '"' . $value . '"',
                'compare' => 'LIKE',
            ];
        }
        $meta_queries[] = $ensemble_size_query;
    }

    $query_args['meta_query'] = count($meta_queries) == 0 ? null : (count($meta_queries) == 1 ? [...$meta_queries] : [
        'relation' => 'AND',
        ...$meta_queries,
    ]);
    $tax_queries = [];
    if (!empty($valid_categories)) {
        $tax_queries[] = [
            'taxonomy' => 'mcategory',
            'field' => 'name',
            'terms' => $valid_categories,
            'compare' => 'IN',
        ];
    }
    if (!empty($valid_genres)) {
        $tax_queries[] = [
            'taxonomy' => 'genre',
            'field' => 'name',
            'terms' => $valid_genres,
            'compare' => 'IN',
        ];
    }
    if (!empty($valid_subgenres)) {
        $tax_queries[] = [
            'taxonomy' => 'subgenre',
            'field' => 'name',
            'terms' => $valid_subgenres,
            'compare' => 'IN',
        ];
    }
    if (!empty($valid_instrumentations)) {
        $tax_queries[] = [
            'taxonomy' => 'instrumentation',
            'field' => 'name',
            'terms' => $valid_instrumentations,
            'compare' => 'IN',
        ];
    }
    if (!empty($valid_settings)) {
        $tax_queries[] = [
            'taxonomy' => 'setting',
            'field' => 'name',
            'terms' => $valid_settings,
            'compare' => 'IN',
        ];
    }
    $query_args['tax_query'] = count($tax_queries) == 0 ? null : (count($tax_queries) == 1 ? [...$tax_queries] : [
        'relation' => 'AND',
        ...$tax_queries,
    ]);

    // Media Tags
    $query_args['media_tags'] = $media_tags;

    $query = new WP_Query($query_args);
    global $wpdb;
    $max_num_results = $query->found_posts;
    $max_num_pages = $query->max_num_pages;
    while ($query->have_posts()) {
        $query->the_post();

        // Get youtube links
        $youtube_video_post_ids = get_field('youtube_videos');
        $youtube_video_data = get_youtube_video_data($youtube_video_post_ids);

        $results[] = [
            'post_id'                => get_the_ID(),
            'title'                  => get_the_title(),
            'name'                   => get_field('name'),
            'rating'                 => get_field('rating'),
            'review_count'           => get_field('review_count'),
            'city'                   => get_field('city'),
            'state'                  => get_field('state'),
            'description'            => get_field('description'),
            'genre'                  => get_the_terms(get_the_ID(), 'genre'),
            'thumbnail_url'          => get_the_post_thumbnail_url(get_the_ID(), 'standard-listing'),
            'tiny_thumbnail_url'     => get_the_post_thumbnail_url(get_the_ID(), 'tiny'),
            'website'                => get_field('website'),
            'facebook_url'           => get_field('facebook_url'),
            'instagram_url'          => get_field('instagram_url'),
            'x_url'                  => get_field('x_url'),
            'youtube_url'            => get_field('youtube_url'),
            'tiktok_url'             => get_field('tiktok_url'),
            'bandcamp_url'           => get_field('bandcamp_url'),
            'spotify_artist_url'     => get_field('spotify_artist_url'),
            'apple_music_artist_url' => get_field('apple_music_artist_url'),
            'soundcloud_url'         => get_field('soundcloud_url'),
            'verified'               => get_field('verified'),
            'youtube_video_data'     => $youtube_video_data,
            'permalink'              => get_permalink(),
        ];
    }

    wp_reset_postdata();
    return [
        'listings'               => $results,
        'valid_types'            => $valid_types,
        'valid_categories'       => $valid_categories,
        'valid_genres'           => $valid_genres,
        'valid_subgenres'        => $valid_subgenres,
        'valid_instrumentations' => $valid_instrumentations,
        'valid_settings'         => $valid_settings,
        'ensemble_size'          => $ensemble_size,
        'min_ensemble_size'      => $min_ensemble_size,
        'max_ensemble_size'      => $max_ensemble_size,
        'max_num_results'        => $max_num_results,
        'max_num_pages'          => $max_num_pages,
        'next_page'              => $next_page,
    ];
}
function validate_listing_types($types) {
    $default_types = ['Artist', 'Musician', 'Producer', 'DJ', 'Sound Engineer', 'Band'];
    return array_values(array_intersect($types, $default_types));
}
function validate_tax_input($tax_input, $taxonomy) {
    $input = rest_sanitize_array($tax_input);
    $terms = get_terms_decoded($taxonomy, 'names');
    $valid_input = array_map('stripslashes', array_filter($input, fn($item) => in_array(stripslashes($item), $terms, true)));
    return $valid_input;
}

// Handle min/max ensemble size filters and specific ensemble sizes filter
function get_ensemble_size_values($args) {
    $ensemble_size_values = [];

    // Convert single or multiple values into an array
    $direct_ensemble_sizes = isset($args['ensemble_size']) ? (array) $args['ensemble_size'] : [];

    foreach ($direct_ensemble_sizes as $value) {
        $value = (int) $value;
        if ($value >= 10) {
            $ensemble_size_values[] = '10+';
        } elseif ($value > 0) {
            $ensemble_size_values[] = (string) $value;
        }
    }

    // Add values from min/max ensemble size range if they are set and not default 1–10
    $min = isset($args['min_ensemble_size']) ? (int) $args['min_ensemble_size'] : null;
    $max = isset($args['max_ensemble_size']) ? (int) $args['max_ensemble_size'] : null;

    if (
        (is_int($min) || is_int($max)) &&
        !($min === 1 && $max === 10)
    ) {
        $min = $min ?? 1;
        $max = $max ?? 10;

        for ($option = $min; $option <= min($max, 9); $option++) {
            $ensemble_size_values[] = (string) $option;
        }
        if ($max >= 10) {
            $ensemble_size_values[] = '10+';
        }
    }

    // Remove duplicates
    return array_values(array_unique($ensemble_size_values));
}
