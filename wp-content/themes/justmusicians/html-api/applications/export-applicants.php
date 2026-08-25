<?php

$application_id = get_query_var('application-id');

// Authorize
$auth = user_can_view_single_application($application_id);
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
fputcsv($out, [
    'Name', 'Email', 'Phone', 'City', 'State', 'Genres', 'Description',
    'Ensemble Size', 'Hire Musicians', 'Website', 'Spotify', 'Apple Music',
    'Instagram', 'Facebook', 'Youtube', 'Bandcamp', 'Soundcloud', 'Applied Date', 'Message']);

foreach ($submission_ids as $submission_id) {
    $listing_id = (int) get_post_meta($submission_id, 'listing', true);
    if (!$listing_id) { continue; }
    $listing = get_listing(['post_id' => $listing_id]);
    if (is_wp_error($listing)) { continue; }

    // Email: listing email first, then first listing owner's user email, else blank
    $email = $listing['email'] ?? '';
    if (empty($email)) {
        $listing_owners = get_listing_owners($listing_id);
        if (!empty($listing_owners)) {
            $owner = get_userdata($listing_owners[0]);
            $email = $owner ? $owner->user_email : '';
        }
    }

    $row = [
        'Name'           => $listing['name'] ?? '',
        'Email'          => $email,
        'Phone'          => $listing['phone'] ?? '',
        'City'           => trim($listing['city'] ?? ''),
        'State'          => trim($listing['state'] ?? ''),
        'Genres'         => implode(', ', $listing['genre'] ?? []),
        'Description'    => $listing['description'] ?? '',
        'Ensemble Size'  => implode(', ', $listing['ensemble_size'] ?? []),
        'Hire Musicians' => $listing['permalink'] ?? '',
        'Website'        => $listing['website'] ?? '',
        'Spotify'        => $listing['spotify_artist_url'] ?? '',
        'Apple Music'    => $listing['apple_music_artist_url'] ?? '',
        'Instagram'      => $listing['instagram_url'] ?? '',
        'Facebook'       => $listing['facebook_url'] ?? '',
        'Youtube'        => $listing['youtube_url'] ?? '',
        'Bandcamp'       => $listing['bandcamp_url'] ?? '',
        'Soundcloud'     => $listing['soundcloud_url'] ?? '',
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
