<?php
/**
 * Plugin Name: Google Sheets Interface
 * Description: Helper functions for syncing WordPress data with Google Sheets
 */

require_once __DIR__ . '/google-api/vendor/autoload.php'; // Google API PHP Client


/**
 * Get authenticated Google Sheets service
 */
function gs_get_service() {
    static $service = null;

    if ($service) {
        return $service;
    }

    $client = new Google_Client();
    $client->setApplicationName('WP Google Sheets Sync');
    $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
    $client->setAuthConfig(GOOGLE_SHEETS_CREDS_PATH); // Make sure this path exists in the env; file is in gitignore similar to "wp-content/mu-plugins/google-sheets/token.json"
    $client->setAccessType('offline');

    $service = new Google_Service_Sheets($client);
    return $service;
}

/**
 * Get all rows indexed by postId (column A)
 * Returns: [ postId => [ 'row' => rowNumber, 'values' => [] ] ]
 */
function gs_get_all_rows_indexed_by_post_id(string $sheet_id, string $sheet_name) {
    $service = gs_get_service();

    $range = $sheet_name;
    $response = $service->spreadsheets_values->get($sheet_id, $range);
    $rows = $response->getValues();

    $indexed = [];

    foreach ($rows as $index => $row) {
        if ($index === 0) {
            continue; // skip header
        }

        if (!empty($row[0])) {
            $indexed[$row[0]] = [
                'row'    => $index + 1,
                'values' => $row
            ];
        }
    }

    return $indexed;
}

/**
 * Append new rows
 */
function gs_append_rows(array $rows, string $sheet_id, string $sheet_name) {
    if (empty($rows)) return;

    $service = gs_get_service();

    $body = new Google_Service_Sheets_ValueRange([
        'values' => $rows
    ]);

    $params = ['valueInputOption' => 'RAW'];

    $service->spreadsheets_values->append(
        $sheet_id,
        $sheet_name,
        $body,
        $params
    );
}

/**
 * Update existing rows
 * $updates = [ rowNumber => [values] ]
 */
function gs_update_rows(array $updates, string $sheet_id, string $sheet_name) {
    if (empty($updates)) return;

    $service = gs_get_service();

    foreach ($updates as $rowNumber => $values) {
        sleep(2); // sleep to avoid rate limit of 60 req per minute
        $range = $sheet_name . '!A' . $rowNumber;

        $body = new Google_Service_Sheets_ValueRange([
            'values' => [$values]
        ]);

        $params = ['valueInputOption' => 'RAW'];

        $service->spreadsheets_values->update(
            $sheet_id,
            $range,
            $body,
            $params
        );
    }
}
