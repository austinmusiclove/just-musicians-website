<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helper function to get field value safely
 */
function em_get_field_value( $array, $key ) {
    return isset( $array[ $key ] ) && $array[ $key ] !== null && $array[ $key ] !== 'null' ? $array[ $key ] : '';
}
?>
<h2>Event Data</h2>
<table class="form-table" style="margin-bottom: 30px; table-layout: fixed; width: 100%;">
    <thead>
        <tr>
            <th style="width: 80px;">Field</th>
            <th>Staged Data</th>
            <th>Current Data</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row"><label>Title</label></th>
            <td><input type="text" name="staged[title]" value="<?php echo esc_attr( em_get_field_value( $staged, 'title' ) ); ?>" style="width: 100%"></td>
            <td><?php echo esc_html( em_get_field_value( $current, 'title' ) ); ?></td>
        </tr>
        <tr>
            <th scope="row"><label>Venue ID</label></th>
            <td><input type="text" name="staged[venue_id]" value="<?php echo esc_attr( em_get_field_value( $staged, 'venue_id' ) ); ?>" style="width: 100%"></td>
            <td><?php echo esc_html( em_get_field_value( $current, 'venue_id' ) ); ?></td>
        </tr>
        <tr>
            <th scope="row"><label>Start Date</label></th>
            <td><input type="date" name="staged[start_date]" value="<?php echo esc_attr( em_get_field_value( $staged, 'start_date' ) ); ?>" style="width: 100%"></td>
            <td><?php echo esc_html( em_get_field_value( $current, 'start_date' ) ); ?></td>
        </tr>
        <tr>
            <th scope="row"><label>End Date</label></th>
            <td><input type="date" name="staged[end_date]" value="<?php echo esc_attr( em_get_field_value( $staged, 'end_date' ) ); ?>" style="width: 100%"></td>
            <td><?php echo esc_html( em_get_field_value( $current, 'end_date' ) ); ?></td>
        </tr>
        <tr>
            <th scope="row"><label>Start Time</label></th>
            <td><input type="time" name="staged[start_time]" value="<?php echo esc_attr( em_get_field_value( $staged, 'start_time' ) ); ?>" style="width: 100%"></td>
            <td><?php echo esc_html( em_get_field_value( $current, 'start_time' ) ); ?></td>
        </tr>
        <tr>
            <th scope="row"><label>End Time</label></th>
            <td><input type="time" name="staged[end_time]" value="<?php echo esc_attr( em_get_field_value( $staged, 'end_time' ) ); ?>" style="width: 100%"></td>
            <td><?php echo esc_html( em_get_field_value( $current, 'end_time' ) ); ?></td>
        </tr>
        <tr>
            <th scope="row"><label>Ages</label></th>
            <td><input type="text" name="staged[ages]" value="<?php echo esc_attr( em_get_field_value( $staged, 'ages' ) ); ?>" style="width: 100%"></td>
            <td><?php echo esc_html( em_get_field_value( $current, 'ages' ) ); ?></td>
        </tr>
        <tr>
            <th scope="row"><label>Price Range</label></th>
            <td><input type="text" name="staged[price_range]" value="<?php echo esc_attr( em_get_field_value( $staged, 'price_range' ) ); ?>" style="width: 100%"></td>
            <td><?php echo esc_html( em_get_field_value( $current, 'price_range' ) ); ?></td>
        </tr>
        <tr>
            <th scope="row"><label>Event Page URL</label></th>
            <td><input type="url" name="staged[event_page_url]" value="<?php echo esc_attr( em_get_field_value( $staged, 'event_page_url' ) ); ?>" style="width: 100%"></td>
            <td><?php echo esc_html( em_get_field_value( $current, 'event_page_url' ) ); ?></td>
        </tr>
        <tr>
            <th scope="row"><label>Ticket URL</label></th>
            <td><input type="url" name="staged[ticket_url]" value="<?php echo esc_attr( em_get_field_value( $staged, 'ticket_url' ) ); ?>" style="width: 100%"></td>
            <td><?php echo esc_html( em_get_field_value( $current, 'ticket_url' ) ); ?></td>
        </tr>
        <tr>
            <th scope="row"><label>Image</label></th>
            <td>
                <?php if ( ! empty( $staged['image_url'] ) ) : ?>
                    <img src="<?php echo esc_url( $staged['image_url'] ); ?>" alt="Staged Image" style="max-width: 100%; height: auto; display: block; border: 1px solid #c3c4c7;" />
                <?php else : ?>
                    No image available.
                <?php endif; ?>
            </td>
            <td>
                <?php if ( ! empty( $current['image_url'] ) ) : ?>
                    <img src="<?php echo esc_url( $current['image_url'] ); ?>" alt="Current Image" style="max-width: 100%; height: auto; display: block; border: 1px solid #c3c4c7;" />
                <?php else : ?>
                    No image available.
                <?php endif; ?>
            </td>
        </tr>
    </tbody>
</table>
