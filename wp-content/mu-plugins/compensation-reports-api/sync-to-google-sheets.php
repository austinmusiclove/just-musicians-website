<?php

$SHEET_ID = '19Ad8Nv-ZQbktd5vBnF0Ivg-tIIKJhwSaNQcHkQg9uk4';
$SHEET_NAME = 'comp_reports';

/**
 * Define Google Sheet columns (order matters!)
 */
$COMP_REPORT_COLUMNS = [
    'postId',
    'title',
    'status',
    'author',
    'author_email',
    'date',
    'modified',
    'venue_name',
    'venue_post_id',
    'total_earnings',
    'minutes_performed',
    'total_performers',
    'comp_structure',
    'payment_speed',
    'payment_method',
    'performing_act_name',
    'performance_date',
    'performance',
    'show_flier_url',
    'review',
    'verified',
    'exclude_from_stats',
    'notes',
];

/**
 * Sync comp_report posts to Google Sheet
 */
function sync_comp_reports_to_google_sheet() {
    global $SHEET_ID;
    global $SHEET_NAME;
    global $COMP_REPORT_COLUMNS;

    if (!function_exists('gs_get_all_rows_indexed_by_post_id')) {
        error_log('Google Sheets helper plugin not loaded.');
        return;
    }

    $existing_rows = gs_get_all_rows_indexed_by_post_id($SHEET_ID, $SHEET_NAME);

    $query = new WP_Query([
        'post_type'      => 'comp_report',
        'posts_per_page' => -1,
        'post_status'    => 'any',
    ]);

    $rows_to_append = [];
    $rows_to_update = [];

    foreach ($query->posts as $post) {
        $row = [];

        foreach ($COMP_REPORT_COLUMNS as $column) {
            switch ($column) {
                case 'postId':
                    $row[] = (string) $post->ID;
                    break;
                case 'title':
                    $row[] = $post->post_title;
                    break;
                case 'status':
                    $row[] = $post->post_status;
                    break;
                case 'author':
                    $row[] = get_the_author_meta('display_name', $post->post_author);
                    break;
                case 'date':
                    $row[] = wp_date('Y-m-d', strtotime($post->post_date));
                    break;
                case 'modified':
                    $row[] = $post->post_modified;
                    break;
                case 'comp_structure':
                    $value = get_post_meta($post->ID, $column, true);
                    if (is_array($value)) { $value = implode(', ', $value); }
                    $row[] = (string) $value;
                    break;
                case 'performance_date':
                    $dt = DateTime::createFromFormat('Ymd', get_post_meta($post->ID, $column, true));
                    $formatted = $dt ? $dt->format('Y-m-d') : '';
                    $row[] = $formatted;
                    break;
                case 'performance':
                    $value = get_post_meta($post->ID, $column, true);
                    if (is_array($value)) { $value = implode(', ', $value); }
                    $row[] = (string) $value;
                    break;
                default:
                    $row[] = get_post_meta($post->ID, $column, true);
            }
        }

        if (isset($existing_rows[$post->ID])) {
            $rows_to_update[$existing_rows[$post->ID]['row']] = $row;
        } else {
            $rows_to_append[] = $row;
        }
    }

    gs_append_rows($rows_to_append, $SHEET_ID, $SHEET_NAME);
    gs_update_rows($rows_to_update, $SHEET_ID, $SHEET_NAME);
}

function sync_google_sheet_to_comp_reports() {
    global $SHEET_ID;
    global $SHEET_NAME;

    if (!function_exists('gs_get_all_rows_indexed_by_post_id')) {
        error_log('Google Sheets helper plugin not loaded.');
        return;
    }

    $existing_rows = gs_get_all_rows_indexed_by_post_id($SHEET_ID, $SHEET_NAME);

    if (empty($existing_rows) || !is_array($existing_rows)) {
        return;
    }

    foreach ($existing_rows as $post_id => $row) {

        // Make sure post_id is valid
        $post_id = intval($post_id);
        if ($post_id <= 0) {
            error_log('skip bad post id');
            continue;
        }

        // Make sure the post exists
        $post = get_post($post_id);
        if (!$post) {
            error_log('skip not a post');
            continue;
        }

        // Make sure it's the correct post type
        if ($post->post_type !== 'comp_report') {
            error_log('skip not a comp_report');
            continue;
        }

        // Only update specific meta fields if they exist in the row
        error_log($post_id);
        error_log(print_r($row['values'][12], true));
        error_log(print_r($row['values'][13], true));
        error_log(print_r($row['values'][14], true));

        // comp_structure
        if (isset($row['values'][12])) {
            error_log('update comp structure');
            update_post_meta($post_id, 'comp_structure', sanitize_text_field($row['values'][12]));
        }

        // payment_speed
        if (isset($row['values'][13])) {
            error_log('update payment speed');
            update_post_meta($post_id, 'payment_speed', sanitize_text_field($row['values'][13]));
        }

        // payment_method
        if (isset($row['values'][14])) {
            error_log('update payment method');
            update_post_meta($post_id, 'payment_method', sanitize_text_field($row['values'][14]));
        }
    }
}

