<?php

function jm_build_location_index() {
    global $wpdb;

    $data_dir = __DIR__ . '/data';
    $inserted_pc   = 0;
    $inserted_city = 0;
    $errors        = [];

    // ---- Postal codes ----
    $pc_table = jm_get_location_pc_table();
    $wpdb->query("DROP TABLE IF EXISTS {$pc_table}");
    jm_create_location_pc_table();

    $pc_files = ['US.txt', 'CA.txt'];

    foreach ($pc_files as $file) {
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
                $result = $wpdb->query("INSERT INTO {$pc_table} (country, postal_code, city, state, state_code, lat, lng) VALUES " . implode(', ', $placeholders));
                if ($result === false) {
                    $errors[] = ['file' => $file, 'error' => $wpdb->last_error];
                } else {
                    $inserted_pc += $result;
                }
                $placeholders = [];
            }
        }

        if (!empty($placeholders)) {
            $result = $wpdb->query("INSERT INTO {$pc_table} (country, postal_code, city, state, state_code, lat, lng) VALUES " . implode(', ', $placeholders));
            if ($result === false) {
                $errors[] = ['file' => $file, 'error' => $wpdb->last_error];
            } else {
                $inserted_pc += $result;
            }
        }

        fclose($handle);
    }

    // ---- City centroids ----
    $city_table = jm_get_location_city_table();
    $wpdb->query("DROP TABLE IF EXISTS {$city_table}");
    jm_create_location_city_table();

    // Load admin1 lookup (US.CA => California, CA.01 => Alberta, etc.)
    $admin1_map = [];
    $admin1_path = $data_dir . '/admin1CodesASCII.txt';
    if (file_exists($admin1_path)) {
        $handle = fopen($admin1_path, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                if ($line === '') { continue; }
                $parts = explode("\t", $line);
                if (count($parts) >= 2) {
                    $admin1_map[$parts[0]] = $parts[1];
                }
            }
            fclose($handle);
        }
    }

    if (empty($admin1_map)) {
        $errors[] = 'admin1CodesASCII.txt not found or empty. Download from https://download.geonames.org/export/dump/admin1CodesASCII.txt';
    }

    $city_file = $data_dir . '/cities5000.txt';
    if (!file_exists($city_file)) {
        $errors[] = 'cities5000.txt not found. Download from https://download.geonames.org/export/dump/cities5000.zip and extract to ' . $city_file;
    } else {
        $handle = fopen($city_file, 'r');
        if (!$handle) {
            $errors[] = 'Could not open: cities5000.txt';
        } else {
            $placeholders = [];

            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                if ($line === '') { continue; }

                $fields = explode("\t", $line);
                if (count($fields) < 11) { continue; }

                $country = $fields[8];
                if ($country !== 'US' && $country !== 'CA') { continue; }

                if ($fields[6] !== 'P') { continue; }

                $admin1_key = $country . '.' . $fields[10];
                $state_name = isset($admin1_map[$admin1_key]) ? $admin1_map[$admin1_key] : '';

                $country    = $wpdb->prepare('%s', $country);
                $city       = $wpdb->prepare('%s', $fields[1]);
                $state_code = $wpdb->prepare('%s', $fields[10]);
                $state      = $wpdb->prepare('%s', $state_name);
                $lat        = !empty($fields[4]) ? (float) $fields[4] : 'NULL';
                $lng        = !empty($fields[5]) ? (float) $fields[5] : 'NULL';

                $placeholders[] = "({$country}, {$city}, {$state_code}, {$state}, {$lat}, {$lng})";

                if (count($placeholders) >= 500) {
                    $result = $wpdb->query("INSERT INTO {$city_table} (country, city, state_code, state, lat, lng) VALUES " . implode(', ', $placeholders));
                    if ($result === false) {
                        $errors[] = ['source' => 'cities5000', 'error' => $wpdb->last_error];
                    } else {
                        $inserted_city += $result;
                    }
                    $placeholders = [];
                }
            }

            if (!empty($placeholders)) {
                $result = $wpdb->query("INSERT INTO {$city_table} (country, city, state_code, state, lat, lng) VALUES " . implode(', ', $placeholders));
                if ($result === false) {
                    $errors[] = ['source' => 'cities5000', 'error' => $wpdb->last_error];
                } else {
                    $inserted_city += $result;
                }
            }

            fclose($handle);
        }
    }

    return new WP_REST_Response([
        'postal_codes_inserted' => $inserted_pc,
        'cities_inserted'       => $inserted_city,
        'errors'                => $errors,
    ], 200);
}

function jm_create_location_pc_table() {
    global $wpdb;
    $table = jm_get_location_pc_table();
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
        INDEX idx_postal_code (postal_code)
    ) {$charset_collate}";

    $wpdb->query($sql);
}

function jm_create_location_city_table() {
    global $wpdb;
    $table = jm_get_location_city_table();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table} (
        id          BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        country     VARCHAR(2) NOT NULL DEFAULT '',
        city        VARCHAR(200) NOT NULL DEFAULT '',
        state_code  VARCHAR(20) NOT NULL DEFAULT '',
        state       VARCHAR(100) NOT NULL DEFAULT '',
        lat         DECIMAL(10,7) DEFAULT NULL,
        lng         DECIMAL(11,7) DEFAULT NULL,
        INDEX idx_city (city)
    ) {$charset_collate}";

    $wpdb->query($sql);
}
