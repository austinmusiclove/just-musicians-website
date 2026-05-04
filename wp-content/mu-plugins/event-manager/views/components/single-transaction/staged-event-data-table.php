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

/**
 * Compare staged vs current values and return highlight class if different.
 */
function em_get_highlight_class( $staged_value, $current_value ) {
    // Normalize values for comparison (handle null, 'null' strings, empty strings)
    $normalize = function( $val ) {
        if ( $val === null || $val === 'null' ) {
            return '';
        }
        return trim( (string) $val );
    };

    $staged_normalized = $normalize( $staged_value );
    $current_normalized = $normalize( $current_value );

    // Return highlight class if different and current is not empty
    if ( $current_normalized !== '' && $staged_normalized !== $current_normalized ) {
        return 'current-data-changed';
    }
    return '';
}

// Check if current data exists and is not empty
$show_current_column = ! empty( $current ) && is_array( $current );
?>
<h2>Event Data</h2>
<style>
    .current-data-changed {
        background-color: #fff3cd;
        border: 1px solid #ffc107;
        padding: 5px;
        border-radius: 3px;
    }
</style>
<table class="form-table" style="margin-bottom: 30px; table-layout: fixed; width: 100%;">
    <colgroup>
        <col style="width: 80px;">
        <?php if ( $show_current_column ) : ?>
            <col style="width: 172px;">
            <col style="width: auto;">
        <?php else : ?>
            <col style="width: auto;">
        <?php endif; ?>
    </colgroup>
    <thead>
        <tr>
            <th>Field</th>
            <th>Staged Data</th>
            <?php if ( $show_current_column ) : ?>
                <th>Current Data</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row"><label>Title</label></th>
            <td><input type="text" name="staged[title]" value="<?php echo esc_attr( em_get_field_value( $staged, 'title' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['title'] ?? '', $current['title'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'title' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Venue ID</label></th>
            <td><input type="text" name="staged[venue_id]" value="<?php echo esc_attr( em_get_field_value( $staged, 'venue_id' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['venue_id'] ?? '', $current['venue_id'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'venue_id' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Venue Name</label></th>
            <td><?php echo esc_html( em_get_field_value( $staged, 'venue_name' ) ); ?></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['venue_name'] ?? '', $current['venue_name'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'venue_name' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Start Date</label></th>
            <td><input type="date" name="staged[start_date]" value="<?php echo esc_attr( em_get_field_value( $staged, 'start_date' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['start_date'] ?? '', $current['start_date'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'start_date' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>End Date</label></th>
            <td><input type="date" name="staged[end_date]" value="<?php echo esc_attr( em_get_field_value( $staged, 'end_date' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['end_date'] ?? '', $current['end_date'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'end_date' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Start Time</label></th>
            <td><input type="time" name="staged[start_time]" value="<?php echo esc_attr( em_get_field_value( $staged, 'start_time' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['start_time'] ?? '', $current['start_time'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'start_time' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>End Time</label></th>
            <td><input type="time" name="staged[end_time]" value="<?php echo esc_attr( em_get_field_value( $staged, 'end_time' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['end_time'] ?? '', $current['end_time'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'end_time' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Ages</label></th>
            <td><input type="text" name="staged[ages]" value="<?php echo esc_attr( em_get_field_value( $staged, 'ages' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['ages'] ?? '', $current['ages'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'ages' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Price Range</label></th>
            <td><input type="text" name="staged[price_range]" value="<?php echo esc_attr( em_get_field_value( $staged, 'price_range' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['price_range'] ?? '', $current['price_range'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'price_range' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Event Page URL</label></th>
            <td><input type="url" name="staged[event_page_url]" value="<?php echo esc_attr( em_get_field_value( $staged, 'event_page_url' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['event_page_url'] ?? '', $current['event_page_url'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'event_page_url' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Ticket URL</label></th>
            <td><input type="url" name="staged[ticket_url]" value="<?php echo esc_attr( em_get_field_value( $staged, 'ticket_url' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['ticket_url'] ?? '', $current['ticket_url'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'ticket_url' ) ); ?></td>
            <?php endif; ?>
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
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['image_url'] ?? '', $current['image_url'] ?? '' ) ); ?>">
                    <?php if ( ! empty( $current['image_url'] ) ) : ?>
                        <img src="<?php echo esc_url( $current['image_url'] ); ?>" alt="Current Image" style="max-width: 100%; height: auto; display: block; border: 1px solid #c3c4c7;" />
                    <?php else : ?>
                        No image available.
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
    </tbody>
</table>
