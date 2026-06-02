<?php

function jm_build_location_index() {
    global $wpdb;
    $table = jm_get_location_table();

    $wpdb->query("DROP TABLE IF EXISTS {$table}");
    jm_create_location_index_table();

    $data_dir = __DIR__;
    $files = [ 'US.txt', 'CA.txt' ];

    $inserted = 0;
    $errors   = [];

    foreach ($files as $file) {
        $path = $data_dir . '/' . $file;
        if (!file_exists($path)) {
            $errors[] = "File not found: {$file}";
            continue;
        }

        $handle = fopen($path, 'r');
        if (!$handle) {
            $errors[] = "Could not open: {$file}";
            continue;
        }

        $batch  = [];
        $values = [];
        $placeholders = [];

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if ($line === '') { continue; }

            $fields = explode("\t", $line);
            if (count($fields) < 11) { continue; }

            $country     = $wpdb->prepare('%s', $fields[0]);
            $postal_code = $wpdb->prepare('%s', $fields[1]);
            $city        = $wpdb->prepare('%s', $fields[2]);
            $state       = $wpdb->prepare('%s', $fields[3]);
            $state_code  = $wpdb->prepare('%s', $fields[4]);
            $lat         = !empty($fields[9]) ? (float) $fields[9] : 'NULL';
            $lng         = !empty($fields[10]) ? (float) $fields[10] : 'NULL';

            $placeholders[] = "({$country}, {$postal_code}, {$city}, {$state}, {$state_code}, {$lat}, {$lng})";

            if (count($placeholders) >= 500) {
                $sql = "INSERT INTO {$table} (country, postal_code, city, state, state_code, lat, lng) VALUES " . implode(', ', $placeholders);
                $result = $wpdb->query($sql);
                if ($result === false) {
                    $errors[] = ['file' => $file, 'error' => $wpdb->last_error];
                } else {
                    $inserted += $result;
                }
                $placeholders = [];
            }
        }

        if (!empty($placeholders)) {
            $sql = "INSERT INTO {$table} (country, postal_code, city, state, state_code, lat, lng) VALUES " . implode(', ', $placeholders);
            $result = $wpdb->query($sql);
            if ($result === false) {
                $errors[] = ['file' => $file, 'error' => $wpdb->last_error];
            } else {
                $inserted += $result;
            }
        }

        fclose($handle);
    }

    return new WP_REST_Response([
        'inserted' => $inserted,
        'errors'   => $errors,
    ], 200);
}

function jm_create_location_index_table() {
    global $wpdb;
    $table = jm_get_location_table();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table} (
        id          BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        postal_code VARCHAR(20) NOT NULL DEFAULT '',
        city        VARCHAR(180) NOT NULL DEFAULT '',
        state       VARCHAR(100) NOT NULL DEFAULT '',
        state_code  VARCHAR(20) NOT NULL DEFAULT '',
        lat         DECIMAL(10,7) DEFAULT NULL,
        lng         DECIMAL(11,7) DEFAULT NULL,
        country     VARCHAR(2) NOT NULL DEFAULT '',
        INDEX idx_postal_code (postal_code),
        INDEX idx_city (city),
        INDEX idx_state_code (state_code),
        INDEX idx_city_state (city, state_code)
    ) {$charset_collate}";

    $wpdb->query($sql);
}
