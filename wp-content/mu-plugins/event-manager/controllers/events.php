<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function event_manager_render_events() {
    $current_page = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
    $venue_id = isset( $_GET['venue_id'] ) && $_GET['venue_id'] !== '' ? sanitize_text_field( $_GET['venue_id'] ) : '';

    $api_url = untrailingslashit( AWS_API_BASE_URL ) . '/events/future';
    $api_args = array( 'page' => $current_page );
    if ( ! empty( $venue_id ) ) {
        $api_args['venue_id'] = $venue_id;
    }
    $api_url = add_query_arg( $api_args, $api_url );

    $response = event_manager_aws_sigv4_request( $api_url );

    $total_count = 0;
    $events = array();
    $total_pages = 0;
    $error_msg = '';

    if ( is_wp_error( $response ) ) {
        $error_msg = $response->get_error_message();
    } else {
        $response_code = wp_remote_retrieve_response_code( $response );
        $body = wp_remote_retrieve_body( $response );

        if ( $response_code === 200 ) {
            $data = json_decode( $body, true );
            if ( isset( $data['total'] ) ) {
                $total_count = intval( $data['total'] );
                $total_pages = max( 1, intval( $data['total_pages'] ) );
            }

            if ( isset( $data['events'] ) && is_array( $data['events'] ) ) {
                $events = $data['events'];
            }
        } else {
            $error_msg = 'API Request failed. Status Code: ' . $response_code . '. Response: ' . esc_html( $body );
        }
    }

    include plugin_dir_path( dirname( __FILE__ ) ) . 'views/events.php';
}
