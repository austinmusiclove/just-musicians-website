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
    // Return highlight class if different and current is not empty
    if ( $staged_value !== $current_value ) {
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
        border-radius: 3px;
        padding: 5px;
    }
    .url-field-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .url-field-wrapper input {
        flex: 1;
    }
    .url-view-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        white-space: nowrap;
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
            <th scope="row"><label>Event Type</label></th>
            <td>
                <select name="staged[event_type]" style="width: 100%">
                    <option value="live_music" <?php selected( em_get_field_value( $staged, 'event_type' ), 'live_music' ); ?>>Live Music</option>
                    <option value="other" <?php selected( em_get_field_value( $staged, 'event_type' ), 'other' ); ?>>Other</option>
                </select>
            </td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['event_type'] ?? '', $current['event_type'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'event_type' ) ); ?></td>
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
            <th scope="row"><label>Description</label></th>
            <td><textarea name="staged[description]" style="width: 100%; min-height: 80px;"><?php echo esc_textarea( em_get_field_value( $staged, 'description' ) ); ?></textarea></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['description'] ?? '', $current['description'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $current, 'description' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Event Page URL</label></th>
            <td>
                <div class="url-field-wrapper">
                    <input type="url" name="staged[event_page_url]" value="<?php echo esc_attr( em_get_field_value( $staged, 'event_page_url' ) ); ?>" style="width: 100%">
                    <?php if ( ! empty( $staged['event_page_url'] ) ) : ?>
                        <a href="<?php echo esc_url( $staged['event_page_url'] ); ?>" target="_blank" class="button url-view-btn" title="View Event Page">View</a>
                    <?php endif; ?>
                </div>
            </td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['event_page_url'] ?? '', $current['event_page_url'] ?? '' ) ); ?>">
                    <?php
                    $event_page_url = em_get_field_value( $current, 'event_page_url' );
                    if ( ! empty( $event_page_url ) ) :
                    ?>
                        <a href="<?php echo esc_url( $event_page_url ); ?>" target="_blank"><?php echo esc_html( $event_page_url ); ?></a>
                    <?php else : ?>
                        <?php echo esc_html( $event_page_url ); ?>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Ticket URL</label></th>
            <td>
                <div class="url-field-wrapper">
                    <input type="url" name="staged[ticket_url]" value="<?php echo esc_attr( em_get_field_value( $staged, 'ticket_url' ) ); ?>" style="width: 100%">
                    <?php if ( ! empty( $staged['ticket_url'] ) ) : ?>
                        <a href="<?php echo esc_url( $staged['ticket_url'] ); ?>" target="_blank" class="button url-view-btn" title="View Tickets">View</a>
                    <?php endif; ?>
                </div>
            </td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['ticket_url'] ?? '', $current['ticket_url'] ?? '' ) ); ?>">
                    <?php
                    $ticket_url = em_get_field_value( $current, 'ticket_url' );
                    if ( ! empty( $ticket_url ) ) :
                    ?>
                        <a href="<?php echo esc_url( $ticket_url ); ?>" target="_blank"><?php echo esc_html( $ticket_url ); ?></a>
                    <?php else : ?>
                        <?php echo esc_html( $ticket_url ); ?>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Image URL</label></th>
            <td>
                <input type="url" name="staged[image_url]" id="staged-image-url-input" value="<?php echo esc_attr( em_get_field_value( $staged, 'image_url' ) ); ?>" style="width: 100%;" oninput="var preview = document.getElementById('staged-image-preview'); if (this.value) { preview.src = this.value; preview.style.display = 'block'; } else { preview.style.display = 'none'; }">
            </td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $staged['image_url'] ?? '', $current['image_url'] ?? '' ) ); ?>">
                    <?php
                    $current_image_url = em_get_field_value( $current, 'image_url' );
                    if ( ! empty( $current_image_url ) ) :
                    ?>
                        <a href="<?php echo esc_url( $current_image_url ); ?>" target="_blank"><?php echo esc_html( $current_image_url ); ?></a>
                    <?php else : ?>
                        No image URL.
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Image Preview</label></th>
            <td>
                <?php if ( ! empty( $staged['image_url'] ) ) : ?>
                    <img id="staged-image-preview" src="<?php echo esc_url( $staged['image_url'] ); ?>" alt="Staged Image" style="max-width: 100%; height: auto; display: block; border: 1px solid #c3c4c7;" />
                <?php else : ?>
                    <img id="staged-image-preview" src="" alt="Staged Image" style="max-width: 100%; height: auto; display: none; border: 1px solid #c3c4c7;" />
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
        <?php if ( ! empty( $staged['page_schema'] ) ) : ?>
        <tr>
            <th scope="row"><label>Page Schema</label></th>
            <td colspan="<?php echo $show_current_column ? 2 : 1; ?>">
                <pre style="max-height: 400px; overflow: auto; background: #f6f7f7; padding: 10px; border: 1px solid #c3c4c7; border-radius: 3px; font-size: 11px; line-height: 1.4; margin: 0; white-space: pre-wrap; word-wrap: break-word;"><?php
                    $schema = $staged['page_schema'];
                    if ( is_array( $schema ) || is_object( $schema ) ) {
                        echo esc_html( json_encode( $schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
                    } else {
                        $decoded = json_decode( $schema, true );
                        if ( json_last_error() === JSON_ERROR_NONE && is_array( $decoded ) ) {
                            echo esc_html( json_encode( $decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
                        } else {
                            echo esc_html( $schema );
                        }
                    }
                ?></pre>
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
