<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Render the Event Manager admin page.
 */
function event_manager_render_admin_page() {
    // Check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    // Fetch data from API
    $api_url = 'https://djzf8h92k0.execute-api.us-east-2.amazonaws.com/prod/staged-transactions/events';
    $response = event_manager_aws_sigv4_request( $api_url );
    
    $staged_count = 0;
    $error_msg = '';

    if ( is_wp_error( $response ) ) {
        $error_msg = $response->get_error_message();
    } else {
        $response_code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );
        
        if ( $response_code === 200 ) {
            $data = json_decode( $body, true );
            if ( isset( $data['count'] ) ) {
                $staged_count = intval( $data['count'] );
            } else {
                $error_msg = 'API response did not contain a "count" field.';
            }
        } else {
            $error_msg = 'API Request failed. Status Code: ' . $response_code . '. Response: ' . esc_html( $body );
        }
    }
    
    // Include the view for rendering
    include plugin_dir_path( dirname( __FILE__ ) ) . 'views/admin-page.php';
}
