<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<h2>Staged Event Data</h2>
<table class="form-table" style="margin-bottom: 30px;">
    <tr>
        <th scope="row"><label>Title</label></th>
        <td><input type="text" name="staged[title]" value="<?php echo esc_attr( isset( $staged['title'] ) ? $staged['title'] : '' ); ?>" class="regular-text"></td>
    </tr>
    <tr>
        <th scope="row"><label>Venue ID</label></th>
        <td><input type="text" name="staged[venue_id]" value="<?php echo esc_attr( isset( $staged['venue_id'] ) ? $staged['venue_id'] : '' ); ?>" class="regular-text"></td>
    </tr>
    <tr>
        <th scope="row"><label>Start Date</label></th>
        <td><input type="date" name="staged[start_date]" value="<?php echo esc_attr( isset( $staged['start_date'] ) ? $staged['start_date'] : '' ); ?>" class="regular-text"></td>
    </tr>
    <tr>
        <th scope="row"><label>End Date</label></th>
        <td><input type="date" name="staged[end_date]" value="<?php echo esc_attr( isset( $staged['end_date'] ) ? $staged['end_date'] : '' ); ?>" class="regular-text"></td>
    </tr>
    <tr>
        <th scope="row"><label>Start Time</label></th>
        <td><input type="time" name="staged[start_time]" value="<?php echo esc_attr( isset( $staged['start_time'] ) ? $staged['start_time'] : '' ); ?>" class="regular-text"></td>
    </tr>
    <tr>
        <th scope="row"><label>End Time</label></th>
        <td><input type="time" name="staged[end_time]" value="<?php echo esc_attr( isset( $staged['end_time'] ) ? $staged['end_time'] : '' ); ?>" class="regular-text"></td>
    </tr>
    <tr>
        <th scope="row"><label>Ages</label></th>
        <td><input type="text" name="staged[ages]" value="<?php echo esc_attr( isset( $staged['ages'] ) ? $staged['ages'] : '' ); ?>" class="regular-text"></td>
    </tr>
    <tr>
        <th scope="row"><label>Price Range</label></th>
        <td><input type="text" name="staged[price_range]" value="<?php echo esc_attr( isset( $staged['price_range'] ) ? $staged['price_range'] : '' ); ?>" class="regular-text"></td>
    </tr>
    <tr>
        <th scope="row"><label>Event Page URL</label></th>
        <td><input type="url" name="staged[event_page_url]" value="<?php echo esc_attr( isset( $staged['event_page_url'] ) ? $staged['event_page_url'] : '' ); ?>" class="regular-text"></td>
    </tr>
    <tr>
        <th scope="row"><label>Ticket URL</label></th>
        <td><input type="url" name="staged[ticket_url]" value="<?php echo esc_attr( isset( $staged['ticket_url'] ) ? $staged['ticket_url'] : '' ); ?>" class="regular-text"></td>
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
