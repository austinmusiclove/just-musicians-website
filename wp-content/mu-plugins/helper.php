<?php

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
*/
function get_youtube_video_id($url) {
    if (empty($url) || !is_string($url)) {
        return false;
    }

    $parsed_url = parse_url($url);

    // Check for youtu.be format
    if (isset($parsed_url['host']) && strpos($parsed_url['host'], 'youtu.be') !== false) {
        return ltrim($parsed_url['path'], '/');
    }

    // Check for youtube.com format with query params
    if (isset($parsed_url['host']) && strpos($parsed_url['host'], 'youtube.com') !== false) {
        if (isset($parsed_url['query'])) {
            parse_str($parsed_url['query'], $query_vars);
            if (isset($query_vars['v']) && preg_match('/^[\w-]{11}$/', $query_vars['v'])) {
                return $query_vars['v'];
            }
        }
    }

    return false;
}

