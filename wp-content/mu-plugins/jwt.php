<?php
/**
 * Plugin Name: JWT
 * Description: Implements Json Web Token
 * Version: 1.0
 * Author: John Filippone
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Register REST API Routes
add_action('rest_api_init', function () {
    register_rest_route('jwt-auth/v1', '/token', [
        'methods' => 'POST',
        'callback' => 'generate_jwt_token',
    ]);
});

// JWT Token generation function
function generate_jwt_token(WP_REST_Request $request) {
    $username = isset($request['username']) ? $request['username'] : null;
    $password = isset($request['password']) ? $request['password'] : null;
    if (!$username or !$password) {
        return new WP_Error('invalid_credentials', 'Missing username or password', ['status' => 400]);
    }

    // Authenticate user with provided credentials
    $user = wp_authenticate($username, $password);
    if (is_wp_error($user)) {
        return new WP_Error('invalid_credentials', 'Invalid username or password', ['status' => 401]);
    }

    // Generate JWT token
    $issued_at = time();
    $expiration_time = $issued_at + (60 * 60); // 1 hour expiration
    $secret_key = JWT_SECRET; // Use a secure secret key
    $payload = [
        'iat' => $issued_at,
        'exp' => $expiration_time,
        'user_id' => $user->ID
    ];

    // Encode payload as JWT
    $jwt = jwt_encode($payload, $secret_key);

    return ['token' => $jwt];
}

// Encode payload to JWT (base64url encoding)
function jwt_encode($payload, $secret_key) {
    $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $header_encoded = base64_url_encode($header);
    $payload_encoded = base64_url_encode(json_encode($payload));

    // Create the signature
    $signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $secret_key, true);
    $signature_encoded = base64_url_encode($signature);

    return "$header_encoded.$payload_encoded.$signature_encoded";
}

// Base64 URL safe encoding
function base64_url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function is_admin_jwt() {
    $decoded_token = check_jwt_token_permission();
    // Ensure the user is an admin
    if (!user_can($decoded_token->user_id, 'administrator')) {
        return new WP_Error('forbidden', 'You do not have permission to access this resource', array('status' => 403));
    }
    return true;
}
// JWT Token verification and permission callback
function check_jwt_token_permission() {
    // Get the token from Authorization header
    $auth_header = getallheaders()['Authorization'] ?? '';
    if (!$auth_header) {
        return new WP_Error('unauthorized', 'Authorization header missing', array('status' => 401));
    }

    // Extract the Bearer token
    if (strpos($auth_header, 'Bearer ') !== false) {
        $token = substr($auth_header, 7); // Remove 'Bearer ' part
    } else {
        return new WP_Error('unauthorized', 'Invalid token format', array('status' => 401));
    }

    // Decode and verify the JWT token
    $secret_key = JWT_SECRET; // Use the same secret key as when creating the token
    $decoded = jwt_decode($token, $secret_key);
    if (!$decoded) {
        return new WP_Error('unauthorized', 'Invalid or expired token', array('status' => 401));
    }
    return $decoded;
}
// Decode JWT Token
function jwt_decode($jwt, $secret_key) {
    list($header_encoded, $payload_encoded, $signature_encoded) = explode('.', $jwt);

    // Decode the base64url encoded payload
    $payload = json_decode(base64_url_decode($payload_encoded));

    if (!$payload || isset($payload->exp) && time() > $payload->exp) {
        return false; // Token is invalid or expired
    }

    // Verify the signature (optional, but recommended)
    $signature = base64_url_decode($signature_encoded);
    $valid_signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $secret_key, true);
    if ($signature !== $valid_signature) {
        return false; // Invalid signature
    }

    return $payload;
}
// Base64 URL safe decode
function base64_url_decode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}
