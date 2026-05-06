<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$future_events = isset( $transaction['venue_future_events'] ) && is_array( $transaction['venue_future_events'] ) ? $transaction['venue_future_events'] : array();
?>
<h2 style="margin-top: 30px;">Venue Future Events</h2>
<?php
$first_event = ! empty( $future_events ) ? reset( $future_events ) : array();
$venue_id = isset( $first_event['venue_id'] ) ? $first_event['venue_id'] : '';
?>
<?php if ( ! empty( $venue_id ) ) : ?>
    <p style="margin: 5px 0 10px;"><strong>Venue ID:</strong> <?php echo esc_html( $venue_id ); ?></p>
<?php endif; ?>
<div class="em-venue-future-events-container" style="max-height: 400px; overflow: auto; border: 1px solid #c3c4c7;">
    <table class="wp-list-table widefat striped table-view-list">
        <thead>
            <tr>
                <th style="width: 80px;">ID</th>
                <th style="width: 40%;">Title</th>
                <th style="width: 140px;">Start Date</th>
                <th style="width: 100px;">Start Time</th>
                <th style="width: 60px;">Event Page</th>
                <th style="width: 60px;">Tickets</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $future_events ) ) : ?>
                <?php foreach ( $future_events as $event ) : ?>
                    <tr>
                        <td><?php echo esc_html( isset( $event['id'] ) ? $event['id'] : '-' ); ?></td>
                        <td><strong><?php echo esc_html( isset( $event['title'] ) ? $event['title'] : '-' ); ?></strong></td>
                        <td><?php echo esc_html( isset( $event['start_date'] ) && ! empty( $event['start_date'] ) ? $event['start_date'] : '-' ); ?></td>
                        <td><?php echo esc_html( isset( $event['start_time'] ) && ! empty( $event['start_time'] ) ? $event['start_time'] : '-' ); ?></td>
                        <td>
                            <?php if ( ! empty( $event['event_page_url'] ) ) : ?>
                                <a href="<?php echo esc_url( $event['event_page_url'] ); ?>" target="_blank">View</a>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ( ! empty( $event['ticket_url'] ) ) : ?>
                                <a href="<?php echo esc_url( $event['ticket_url'] ); ?>" target="_blank">View</a>
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No future events found for this venue.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Start Date</th>
                <th>Start Time</th>
                <th>Event Page</th>
                <th>Tickets</th>
            </tr>
        </tfoot>
    </table>
</div>
