<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div style="margin-top: 20px;">
    <table class="wp-list-table widefat fixed striped table-view-list">
        <thead>
            <tr>
                <th scope="col" style="width: 80px;">Image</th>
                <th scope="col" style="width: 80px;">ID</th>
                <th scope="col">Event Title</th>
                <th scope="col" style="width: 80px;">Venue ID</th>
                <th scope="col" style="width: 150px;">Venue</th>
                <th scope="col" style="width: 120px;">Start Date</th>
                <th scope="col" style="width: 100px;">Event Page</th>
                <th scope="col" style="width: 150px;">Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $events ) && is_array( $events ) ) : ?>
                <?php foreach ( $events as $event ) : ?>
                    <tr>
                        <td>
                            <?php if ( ! empty( $event['image_url'] ) ) : ?>
                                <img src="<?php echo esc_url( $event['image_url'] ); ?>" alt="" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #c3c4c7;" />
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html( isset( $event['id'] ) ? $event['id'] : '-' ); ?></td>
                        <td><strong><?php echo esc_html( isset( $event['title'] ) ? $event['title'] : '-' ); ?></strong></td>
                        <td><?php echo esc_html( isset( $event['venue_id'] ) ? $event['venue_id'] : '-' ); ?></td>
                        <td><?php echo esc_html( isset( $event['venue_name'] ) ? $event['venue_name'] : '-' ); ?></td>
                        <td><?php echo esc_html( isset( $event['start_date'] ) ? $event['start_date'] : '-' ); ?></td>
                        <td>
                            <?php if ( ! empty( $event['event_page_url'] ) ) : ?>
                                <a href="<?php echo esc_url( $event['event_page_url'] ); ?>" target="_blank">View</a>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
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
                    <td colspan="8" style="text-align: center; padding: 20px;">No events found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th scope="col">Image</th>
                <th scope="col">ID</th>
                <th scope="col">Event Title</th>
                <th scope="col">Venue ID</th>
                <th scope="col">Venue</th>
                <th scope="col">Start Date</th>
                <th scope="col">Event Page</th>
                <th scope="col">Created At</th>
            </tr>
        </tfoot>
    </table>
</div>
