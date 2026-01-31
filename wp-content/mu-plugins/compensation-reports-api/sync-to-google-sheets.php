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
                    $row[] = implode(", ", get_post_meta($post->ID, $column, true));
                    break;
                case 'performance_date':
                    $dt = DateTime::createFromFormat('Ymd', get_post_meta($post->ID, $column, true));
                    $formatted = $dt ? $dt->format('Y-m-d') : '';
                    $row[] = $formatted;
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

