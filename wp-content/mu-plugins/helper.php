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

