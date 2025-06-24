<?php


// Failed experiement for now


// If search term is provided sort is done by relevance then by rank then by post id
// If search term is not provided sort is done by rank then by post id
// Required index (run this once on db): SET SESSION sql_mode='NO_ENGINE_SUBSTITUTION'; ALTER TABLE wp_posts ADD FULLTEXT(post_title); ALTER TABLE wp_posts ADD FULLTEXT(post_title, post_content); SHOW CREATE TABLE wp_posts;
// If need to reverse this operation do: SET SESSION sql_mode='NO_ENGINE_SUBSTITUTION'; ALTER TABLE wp_posts DROP INDEX post_title; ALTER TABLE wp_posts DROP INDEX post_title_2; SHOW CREATE TABLE wp_posts;


if ( ! defined( 'ABSPATH' ) ) { exit; }

add_filter('posts_where',   'listings_search_where'        , 10, 2);
add_filter('posts_join',    'listings_search_algo_join'    , 10, 2);
add_filter('posts_orderby', 'listings_search_algo_orderby' , 10, 2);
add_filter('posts_groupby', 'listings_search_algo_groupby' , 10, 2);
//add_filter('posts_search',  'listings_search_algo_disable_default_search', 10, 2);

function listings_search_where($where, $query) {
    if (!is_a($query, 'WP_Query') || !$query->get('use_listings_search_algo')) return $where;

    global $wpdb;

    $slugs = $query->get('mediatags', []);
    if (empty($slugs)) return $where;

    // Get term IDs from slugs
    $terms = get_terms([
        'taxonomy' => 'mediatag',
        'hide_empty' => false,
        'slug' => $slugs,
    ]);
    $term_ids = wp_list_pluck($terms, 'term_id');

    if (!empty($term_ids)) {
        $term_ids_sql = implode(',', array_map('intval', $term_ids));
        $where .= " AND mt_tt.term_id IN ($term_ids_sql)";
    }

    return $where;
}


function listings_search_algo_join($join, $query) {
    if (!is_a($query, 'WP_Query') || !$query->get('use_listings_search_algo')) return $join;

    global $wpdb;

    $mediatag_names = $query->get('media_tags', []);
    $mediatag_terms = get_terms([
        'taxonomy' => 'mediatag',
        'hide_empty' => false,
        'name' => $mediatag_names,
    ]);
    $mediatag_ids = wp_list_pluck($mediatag_terms, 'term_id');
    if (!empty($mediatag_ids)) {
        $mediatag_ids_sql = implode(',', array_map('intval', $mediatag_ids));
        $join .= "
            LEFT JOIN {$wpdb->term_relationships} AS mt_rel
                ON {$wpdb->posts}.ID = mt_rel.object_id
            LEFT JOIN {$wpdb->term_taxonomy} AS mt_tt
                ON mt_rel.term_taxonomy_id = mt_tt.term_taxonomy_id
                AND mt_tt.taxonomy = 'mediatag'
                AND mt_tt.term_id IN ($mediatag_ids_sql)
        ";
    }

    $join .= " LEFT JOIN {$wpdb->postmeta} AS rank_meta
                ON {$wpdb->posts}.ID = rank_meta.post_id
                AND rank_meta.meta_key = 'rank' ";

    return $join;
}

function listings_search_algo_orderby($orderby, $query) {
    if (!is_a($query, 'WP_Query') || !$query->get('use_listings_search_algo')) return $orderby;

    global $wpdb;
    $mediatag_names = $query->get('media_tags', []);
    if (!empty($mediatag_names)) {
        return "
            COUNT(DISTINCT mt_tt.term_id) DESC,
            CAST(rank_meta.meta_value AS UNSIGNED) DESC,
            {$wpdb->posts}.ID ASC
        ";
    }

    // No search term → use custom ranking
    return "
        CAST(rank_meta.meta_value AS UNSIGNED) DESC,
        {$wpdb->posts}.ID ASC
    ";
}

function listings_search_algo_groupby($groupby, $query) {
    if (!is_a($query, 'WP_Query') || !$query->get('use_listings_search_algo')) return $groupby;

    global $wpdb;
    return "{$wpdb->posts}.ID";
}



/*
function listings_search_algo_disable_default_search($search, $query) {
    if (!is_a($query, 'WP_Query') || !$query->get('use_listings_search_algo')) return $search;
    return ''; // Bypass WP's default search
}
*/
