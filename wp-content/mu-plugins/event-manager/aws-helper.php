<?php
/**
 * Helper to generate AWS Signature Version 4 headers.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function event_manager_aws_sigv4_request( $url, $method = 'GET', $body = '' ) {
    if ( ! defined( 'AWS_API_ACCESS_KEY' ) || ! defined( 'AWS_API_SECRET_KEY' ) || empty( AWS_API_ACCESS_KEY ) || empty( AWS_API_SECRET_KEY ) ) {
        return new WP_Error( 'missing_keys', 'AWS_API_ACCESS_KEY or AWS_API_SECRET_KEY is not defined or empty in wp-config.php.' );
    }

    $access_key = AWS_API_ACCESS_KEY;
    $secret_key = AWS_API_SECRET_KEY;

    $parsed_url = parse_url( $url );
    $host = $parsed_url['host'];
    $path = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '/';
    $query = isset( $parsed_url['query'] ) ? $parsed_url['query'] : '';

    // Hardcoded per your AWS API Gateway endpoint
    $region  = 'us-east-2';
    $service = 'execute-api';

    $timestamp = gmdate( 'Ymd\THis\Z' );
    $date      = substr( $timestamp, 0, 8 );

    // 1. Task 1: Create a Canonical Request
    $canonical_uri     = $path;
    $canonical_querystring = $query;
    $canonical_headers = "host:" . $host . "\nx-amz-date:" . $timestamp . "\n";
    $signed_headers    = 'host;x-amz-date';
    $payload_hash      = hash( 'sha256', $body );

    $canonical_request = $method . "\n" .
                         $canonical_uri . "\n" .
                         $canonical_querystring . "\n" .
                         $canonical_headers . "\n" .
                         $signed_headers . "\n" .
                         $payload_hash;

    // 2. Task 2: Create a String to Sign
    $algorithm = 'AWS4-HMAC-SHA256';
    $credential_scope = $date . '/' . $region . '/' . $service . '/' . 'aws4_request';
    $string_to_sign = $algorithm . "\n" .
                      $timestamp . "\n" .
                      $credential_scope . "\n" .
                      hash( 'sha256', $canonical_request );

    // 3. Task 3: Calculate the Signature
    $kSecret  = 'AWS4' . $secret_key;
    $kDate    = hash_hmac( 'sha256', $date, $kSecret, true );
    $kRegion  = hash_hmac( 'sha256', $region, $kDate, true );
    $kService = hash_hmac( 'sha256', $service, $kRegion, true );
    $kSigning = hash_hmac( 'sha256', 'aws4_request', $kService, true );
    $signature = hash_hmac( 'sha256', $string_to_sign, $kSigning );

    // 4. Task 4: Add the signing information to the request
    $authorization_header = $algorithm . ' ' .
                            'Credential=' . $access_key . '/' . $credential_scope . ', ' .
                            'SignedHeaders=' . $signed_headers . ', ' .
                            'Signature=' . $signature;

    $headers = array(
        'Host'          => $host,
        'X-Amz-Date'    => $timestamp,
        'Authorization' => $authorization_header,
        'Accept'        => 'application/json'
    );

    $args = array(
        'method'  => $method,
        'headers' => $headers,
        'body'    => $body ? $body : null,
        'timeout' => 30,
    );

    return wp_remote_request( $url, $args );
}
