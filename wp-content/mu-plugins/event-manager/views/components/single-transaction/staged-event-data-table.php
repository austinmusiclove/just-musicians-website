<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<h2>Staged Event Data</h2>
<table class="form-table" style="margin-bottom: 30px;">
    <tr>
        <th scope="row"><label>Title</label></th>
        <td><?php echo esc_html( isset( $staged['title'] ) ? $staged['title'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>Venue ID</label></th>
        <td><?php echo esc_html( isset( $staged['venue_id'] ) ? $staged['venue_id'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>Start Date</label></th>
        <td><?php echo esc_html( isset( $staged['start_date'] ) ? $staged['start_date'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>End Date</label></th>
        <td><?php echo esc_html( isset( $staged['end_date'] ) ? $staged['end_date'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>Start Time</label></th>
        <td><?php echo esc_html( isset( $staged['start_time'] ) ? $staged['start_time'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>End Time</label></th>
        <td><?php echo esc_html( isset( $staged['end_time'] ) ? $staged['end_time'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>Ages</label></th>
        <td><?php echo esc_html( isset( $staged['ages'] ) ? $staged['ages'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>Price Range</label></th>
        <td><?php echo esc_html( isset( $staged['price_range'] ) ? $staged['price_range'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>Event Page URL</label></th>
        <td>
            <?php if ( ! empty( $staged['event_page_url'] ) ) : ?>
                <a href="<?php echo esc_url( $staged['event_page_url'] ); ?>" target="_blank"><?php echo esc_html( $staged['event_page_url'] ); ?></a>
            <?php else : ?>
                -
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th scope="row"><label>Ticket URL</label></th>
        <td>
            <?php if ( ! empty( $staged['ticket_url'] ) ) : ?>
                <a href="<?php echo esc_url( $staged['ticket_url'] ); ?>" target="_blank"><?php echo esc_html( $staged['ticket_url'] ); ?></a>
            <?php else : ?>
                -
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th scope="row"><label>Image</label></th>
        <td>
            <?php if ( ! empty( $staged['image_url'] ) ) : ?>
                <img src="<?php echo esc_url( $staged['image_url'] ); ?>" alt="Event Image" style="max-width: 300px; height: auto; display: block; border: 1px solid #c3c4c7;" />
            <?php else : ?>
                No image available.
            <?php endif; ?>
        </td>
    </tr>
</table>
