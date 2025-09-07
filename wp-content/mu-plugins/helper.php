<?php

function user_logged_in() {
    if (!is_user_logged_in()) {
        return new WP_Error('unauthorized', 'You must be logged in to perform this action.', ['status' => 401]);
    }
    return true;
}

// Cleans name property of file
function custom_sanitize_file($file) {
    $file['name'] = sanitize_file_name($file['name']);
    return $file;
}

// sanitize array, remove blank values with array_filter, reindex array with array_values
// useful with array inputs where i always pass a blank so that the user has a way to erase all options; otherwise no argument is passed to the back end and no edit happens
// reindexing is useful so that json_encode turns it into an array instead of an object
function custom_sanitize_array($arr) {
    return array_values(array_filter(array_map('sanitize_text_field', rest_sanitize_array($arr))));
}

function get_thumbnails_from_listings($listing_post_ids) {
    $thumbnails = [];
    if (count($listing_post_ids) >= 4) {
        $thumbnails[] = get_the_post_thumbnail_url($listing_post_ids[0], 'standard-listing');
        $thumbnails[] = get_the_post_thumbnail_url($listing_post_ids[1], 'standard-listing');
        $thumbnails[] = get_the_post_thumbnail_url($listing_post_ids[2], 'standard-listing');
        $thumbnails[] = get_the_post_thumbnail_url($listing_post_ids[3], 'standard-listing');
    } else if (count($listing_post_ids) >= 1) {
        $thumbnails[] = get_the_post_thumbnail_url($listing_post_ids[0], 'standard-listing');
    }
    return array_filter($thumbnails);
}
function is_valid_url($url) {
    // Trim whitespace
    $url = trim($url);

    // Check if empty
    if (empty($url)) {
        return new WP_Error('invalid_url', 'The URL is empty. Please enter a valid website address.');
    }

    // Check scheme (must start with http:// or https://)
    if (!preg_match('/^https?:\/\//i', $url)) {
        return new WP_Error('invalid_scheme', 'The URL must start with http:// or https://');
    }

    // Validate format using filter_var
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return new WP_Error('malformed_url', 'The URL format is invalid. Please check for typos or missing parts like domain name or slashes.');
    }

    // Optional: Check for valid host name
    $host = parse_url($url, PHP_URL_HOST);
    if (!$host || strpos($host, '.') === false) {
        return new WP_Error('invalid_host', 'The URL must contain a valid domain (e.g., example.com).');
    }

    return true;
}

/* Valid Youtube url examples
https://www.youtube.com/watch?time_continue=1&v=PsTm7r4Ijgc&embeds_referring_euri=https%3A%2F%2Fjimmyeatbrisket.com%2F&source_ve_path=Mjg2NjY
https://www.youtube.com/watch?v=keKIhiCGl48&list=PLBJm7uMkVCGi7nXHCLr0ke4_bfD5Qf5Iv&index=8
https://www.youtube.com/watch?list=PLBJm7uMkVCGi7nXHCLr0ke4_bfD5Qf5Iv&v=SnLHff64gNk
https://www.youtube.com/watch?v=ivt9ypn_4kQ&ab_channel=AdamDodson
https://www.youtube.com/watch?v=Onrg9ndx2Vs&feature=youtu.be
https://www.youtube.com/watch?app=desktop&v=4GBwBY272nc
https://www.youtube.com/watch?v=3TBAgTSqQuw&t=16s
https://www.youtube.com/watch?v=v9C3UHya0EY
https://youtube.com/watch?v=QhEOTjfa-wA&si=ep4Sz0B6tudIKRk5
https://youtu.be/qzAZgdrNttY
https://youtu.be/x-8zu6obNNA
https://youtu.be/kNtVnhFNA-M?feature=shared
https://youtu.be/zMgyVfpxJpE?si=j2KgWyFT4gc5XU0X
https://m.youtube.com/watch?v=w7lX4VUOecw
https://m.youtube.com/watch?v=jAWWcnpc_RY&pp=ygUQRGlldHJpY2ggY2FsaG91bg%3D%3D
https://www.youtube.com/shorts/En9MaAJtc2I
https://www.youtube.com/shorts/ljKzyzjtB8o?si=LtarNFxW6TF7gnf9
https://youtube.com/shorts/pYWdyCHSy2o?si=ctLKAkHJO7_qxn-i
*/
function get_youtube_video_id($url) {
    if (empty($url) || !is_string($url)) {
        return false;
    }

    $parsed_url = parse_url($url);
    $host = $parsed_url['host'] ?? '';
    $path = $parsed_url['path'] ?? '';
    $query = $parsed_url['query'] ?? '';


    // Check for youtu.be format
    if (strpos($host, 'youtu.be') !== false) {
        $id = ltrim($path, '/');
        return preg_match('/^[\w-]{11}$/', $id) ? $id : false;
    }

    // Check for youtube.com/watch?v= format
    if (strpos($host, 'youtube.com') !== false || strpos($host, 'm.youtube.com') !== false) {
        parse_str($query, $query_vars);
        if (isset($query_vars['v']) && preg_match('/^[\w-]{11}$/', $query_vars['v'])) {
            return $query_vars['v'];
        }

        // Check for /shorts/{videoId} path
        if (preg_match('#^/shorts/([\w-]{11})#', $path, $matches)) {
            return $matches[1];
        }
    }

    return false;
}

function get_display_name($user_id) {
    $display_name = get_userdata($user_id)->display_name;
    return clean_display_name($display_name);
}
// Remove domain if display name is an email
function clean_display_name($display_name) {
    return filter_var($display_name, FILTER_VALIDATE_EMAIL) ? explode('@', $display_name)[0] : $display_name;
}

// Parse files and file meta data
function custom_parse_file($data, $file_index) {
    $data = custom_parse_json($data);
    if (isset($_FILES[$file_index])) {
        $file['name'] = sanitize_file_name($_FILES[$file_index]['name']);
        $data['file'] = $_FILES[$file_index];
    }
    return $data;
}
function custom_parse_ordered_files($data, $file_index) {
    $ordered_files = [];

    // Parse meta data and files
    $data = custom_parse_json($data);
    $parsed_files = parse_files($file_index);

    // if has upload index add file from that index
    foreach ($data as $image_data) {
        if (isset($image_data['upload_index']) and is_int($image_data['upload_index']) and $image_data['upload_index'] < count($parsed_files)) {
            $image_data['file'] = $parsed_files[$image_data['upload_index']];
        }
        $ordered_files[] = $image_data;
    }
    return $ordered_files;
}
function parse_files($file_index) {
    $parsed_files = [];;
    if (isset($_FILES[$file_index])) {
        $files = $_FILES[$file_index];
        $count = count($files['name']);
        for ($iter = 0; $iter < $count; $iter++) {
            $parsed_files[] = [
                'name'     => sanitize_file_name($files['name'][$iter]),
                'type'     => $files['type'][$iter],
                'tmp_name' => $files['tmp_name'][$iter],
                'error'    => $files['error'][$iter],
                'size'     => $files['size'][$iter],
            ];
        }
    }
    return $parsed_files;
}
// check youtube url validity
// generate video id from valid urls and set video id using that in case the video id that was send in was wrong
function custom_parse_youtube_video_data($json) {
    $youtube_video_data = custom_parse_json($json);
    $youtube_video_ids = [];
    if ($youtube_video_data and is_array($youtube_video_data)) {
        foreach($youtube_video_data as $index => $video_data) {
            $video_id = get_youtube_video_id($video_data['url']);
            if ($video_id) {
                $youtube_video_ids[] = $video_id;
                $youtube_video_data[$index]['video_id'] = $video_id;
            }
        }
    }
    return $youtube_video_data;
}
function custom_parse_json($json) {
    return json_decode(stripslashes($json), true);
}

function get_state_code($stateName) {
    $states = [
        "Alabama" => "AL",
        "Alaska" => "AK",
        "Arizona" => "AZ",
        "Arkansas" => "AR",
        "California" => "CA",
        "Colorado" => "CO",
        "Connecticut" => "CT",
        "Delaware" => "DE",
        "Florida" => "FL",
        "Georgia" => "GA",
        "Hawaii" => "HI",
        "Idaho" => "ID",
        "Illinois" => "IL",
        "Indiana" => "IN",
        "Iowa" => "IA",
        "Kansas" => "KS",
        "Kentucky" => "KY",
        "Louisiana" => "LA",
        "Maine" => "ME",
        "Maryland" => "MD",
        "Massachusetts" => "MA",
        "Michigan" => "MI",
        "Minnesota" => "MN",
        "Mississippi" => "MS",
        "Missouri" => "MO",
        "Montana" => "MT",
        "Nebraska" => "NE",
        "Nevada" => "NV",
        "New Hampshire" => "NH",
        "New Jersey" => "NJ",
        "New Mexico" => "NM",
        "New York" => "NY",
        "North Carolina" => "NC",
        "North Dakota" => "ND",
        "Ohio" => "OH",
        "Oklahoma" => "OK",
        "Oregon" => "OR",
        "Pennsylvania" => "PA",
        "Rhode Island" => "RI",
        "South Carolina" => "SC",
        "South Dakota" => "SD",
        "Tennessee" => "TN",
        "Texas" => "TX",
        "Utah" => "UT",
        "Vermont" => "VT",
        "Virginia" => "VA",
        "Washington" => "WA",
        "West Virginia" => "WV",
        "Wisconsin" => "WI",
        "Wyoming" => "WY"
    ];

    $stateName = trim($stateName);

    return isset($states[$stateName]) ? $states[$stateName] : null;
}
