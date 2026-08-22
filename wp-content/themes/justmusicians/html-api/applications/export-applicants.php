<?php

$application_id = get_query_var('application-id');

// Authorize
$auth = require_application_authorship($application_id);
if (is_wp_error($auth) || !$auth) {
    echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'You are not authorized to export these applicants\' })"></span>';
    exit;
}

$result         = get_applicants($application_id, ['nopaging' => true]);
$submission_ids = $result['submission_ids'];

$filename = 'applicants-' . sanitize_title(get_the_title($application_id)) . '-' . date('Y-m-d') . '.csv';

nocache_headers();
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

echo "\xEF\xBB\xBF";
$out = fopen('php://output', 'w');
fputcsv($out, ['Name', 'Email', 'Email Verified', 'City', 'State', 'Genres', 'Applied Date', 'Message']);

foreach ($submission_ids as $submission_id) {
    $listing_id = (int) get_post_meta($submission_id, 'listing', true);
    if (!$listing_id) { continue; }
    $listing = get_listing(['post_id' => $listing_id]);
    if (is_wp_error($listing)) { continue; }

    // Pending listing means the applicant signed up without verifying their email
    $email_verified = get_post_status($listing_id) === 'pending' ? 'No' : 'Yes';

    $row = [
        'Name'           => $listing['name'] ?? '',
        'Email'          => $listing['email'] ?? '',
        'Email Verified' => $email_verified,
        'City'           => trim($listing['city'] ?? ''),
        'State'          => trim($listing['state'] ?? ''),
        'Genres'         => implode(', ', $listing['genre'] ?? []),
        'Applied Date'   => get_the_modified_time('Y-m-d', $submission_id),
        'Message'        => get_post_meta($submission_id, 'message', true),
    ];

    // Neutralize spreadsheet formula injection
    foreach ($row as &$value) {
        if (preg_match('/^[=+\-@\t\r]/', (string) $value)) {
            $value = "'" . $value;
        }
    }
    unset($value);

    fputcsv($out, array_values($row));
}

exit;
