<?php


// Failed experiement for now


// If search term is provided sort is done by relevance then by rank then by post id
// If search term is not provided sort is done by rank then by post id
// Required index (run this once on db): SET SESSION sql_mode='NO_ENGINE_SUBSTITUTION'; ALTER TABLE wp_posts ADD FULLTEXT(post_title); ALTER TABLE wp_posts ADD FULLTEXT(post_title, post_content); SHOW CREATE TABLE wp_posts;
// If need to reverse this operation do: SET SESSION sql_mode='NO_ENGINE_SUBSTITUTION'; ALTER TABLE wp_posts DROP INDEX post_title; ALTER TABLE wp_posts DROP INDEX post_title_2; SHOW CREATE TABLE wp_posts;


if ( ! defined( 'ABSPATH' ) ) { exit; }

add_filter('posts_join', 'listings_search_algo_join', 10, 2);
add_filter('posts_where', 'listings_search_algo_where', 10, 2);
add_filter('posts_orderby', 'listings_search_algo_orderby', 10, 2);
add_filter('posts_search', 'listings_search_algo_disable_default_search', 10, 2);

function listings_search_algo_join($join, $query) {
    if (!is_a($query, 'WP_Query') || !$query->get('use_listings_search_algo')) return $join;

    global $wpdb;
    return $join . " LEFT JOIN {$wpdb->postmeta} AS rank_meta ON {$wpdb->posts}.ID = rank_meta.post_id AND rank_meta.meta_key = 'rank' ";
}

function listings_search_algo_where($where, $query) {
    if (!is_a($query, 'WP_Query') || !$query->get('use_listings_search_algo')) return $where;

    $search_term = $query->get('s');
    if (!empty($search_term)) {
        global $wpdb;
        $escaped = esc_sql($search_term);
        $where .= " AND MATCH({$wpdb->posts}.post_title, {$wpdb->posts}.post_content) AGAINST ('{$escaped}' IN BOOLEAN MODE) ";
    }

    return $where;
}

function listings_search_algo_orderby($orderby, $query) {
    if (!is_a($query, 'WP_Query') || !$query->get('use_listings_search_algo')) return $orderby;

    global $wpdb;
    $search_term = $query->get('s');

    if (!empty($search_term)) {
        $escaped = esc_sql($search_term);
        // Order by title relevance, then rank, then post ID
        return "
            MATCH({$wpdb->posts}.post_title) AGAINST ('{$escaped}' IN BOOLEAN MODE) DESC,
            CAST(rank_meta.meta_value AS UNSIGNED) DESC,
            {$wpdb->posts}.ID ASC
        ";
    }

    // If no search term, just order by rank and post ID
    return "
        CAST(rank_meta.meta_value AS UNSIGNED) DESC,
        {$wpdb->posts}.ID ASC
    ";
}

function listings_search_algo_disable_default_search($search, $query) {
    if (!is_a($query, 'WP_Query') || !$query->get('use_listings_search_algo')) return $search;
    return ''; // Bypass WP's default search
}
