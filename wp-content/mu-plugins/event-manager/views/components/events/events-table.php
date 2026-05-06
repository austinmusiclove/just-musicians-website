<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div style="margin-top: 20px;">
    <table class="wp-list-table widefat fixed striped table-view-list">
        <thead>
            <tr>
                <th scope="col" style="width: 80px;">ID</th>
                <th scope="col">Event Title</th>
                <th scope="col" style="width: 180px;">Venue</th>
                <th scope="col" style="width: 150px;">Start Date</th>
                <th scope="col" style="width: 150px;">End Date</th>
                <th scope="col" style="width: 180px;">Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $events ) && is_array( $events ) ) : ?>
                <?php foreach ( $events as $event ) : ?>
                    <tr>
                        <td><?php echo esc_html( isset( $event['id'] ) ? $event['id'] : '-' ); ?></td>
                        <td>
                            <?php
                                $event_title = isset( $event['title'] ) ? $event['title'] : '-';
                                echo '<strong>' . esc_html( $event_title ) . '</strong>';
                            ?>
                        </td>
                        <td><?php echo esc_html( isset( $event['venue_name'] ) ? $event['venue_name'] : '-' ); ?></td>
                        <td><?php echo esc_html( isset( $event['start_date'] ) ? $event['start_date'] : '-' ); ?></td>
                        <td><?php echo esc_html( isset( $event['end_date'] ) ? $event['end_date'] : '-' ); ?></td>
                        <td>
                            <?php
                                if ( ! empty( $event['created_at'] ) ) {
                                    $timestamp = strtotime( $event['created_at'] );
                                    $format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
                                    echo esc_html( wp_date( $format, $timestamp ) );
                                } else {
                                    echo '-';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No events found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Event Title</th>
                <th scope="col">Venue</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Created At</th>
            </tr>
        </tfoot>
    </table>
</div>
