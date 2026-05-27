<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'em_get_field_value' ) ) {
    function em_get_field_value( $array, $key ) {
        return isset( $array[ $key ] ) && $array[ $key ] !== null && $array[ $key ] !== 'null' ? $array[ $key ] : '';
    }
}

if ( ! function_exists( 'em_get_highlight_class' ) ) {
    function em_get_highlight_class( $staged_value, $current_value ) {
        if ( $staged_value !== $current_value ) {
            return 'current-data-changed';
        }
        return '';
    }
}

$show_current_column = ! empty( $cd ) && is_array( $cd );
?>
<table class="form-table" style="margin-bottom: 0; table-layout: fixed; width: 100%;">
    <colgroup>
        <col style="width: 100px;">
        <col style="width: auto;">
        <?php if ( $show_current_column ) : ?>
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
            <td><input type="text" name="transactions[<?php echo $index; ?>][staged][title]" value="<?php echo esc_attr( em_get_field_value( $sd, 'title' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $sd['title'] ?? '', $cd['title'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $cd, 'title' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Venue Name</label></th>
            <td><?php echo esc_html( em_get_field_value( $sd, 'venue_name' ) ); ?></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $sd['venue_name'] ?? '', $cd['venue_name'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $cd, 'venue_name' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Start Date</label></th>
            <td><input type="date" name="transactions[<?php echo $index; ?>][staged][start_date]" value="<?php echo esc_attr( em_get_field_value( $sd, 'start_date' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $sd['start_date'] ?? '', $cd['start_date'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $cd, 'start_date' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Start Time</label></th>
            <td><input type="time" name="transactions[<?php echo $index; ?>][staged][start_time]" value="<?php echo esc_attr( em_get_field_value( $sd, 'start_time' ) ); ?>" style="width: 100%"></td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $sd['start_time'] ?? '', $cd['start_time'] ?? '' ) ); ?>"><?php echo esc_html( em_get_field_value( $cd, 'start_time' ) ); ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Event Page URL</label></th>
            <td>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <input type="url" name="transactions[<?php echo $index; ?>][staged][event_page_url]" value="<?php echo esc_attr( em_get_field_value( $sd, 'event_page_url' ) ); ?>" style="flex: 1">
                    <?php if ( ! empty( $sd['event_page_url'] ) ) : ?>
                        <a href="<?php echo esc_url( $sd['event_page_url'] ); ?>" target="_blank" class="button" style="white-space: nowrap;">View</a>
                    <?php endif; ?>
                </div>
            </td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $sd['event_page_url'] ?? '', $cd['event_page_url'] ?? '' ) ); ?>">
                    <?php $cp = em_get_field_value( $cd, 'event_page_url' ); ?>
                    <?php if ( ! empty( $cp ) ) : ?>
                        <a href="<?php echo esc_url( $cp ); ?>" target="_blank"><?php echo esc_html( $cp ); ?></a>
                    <?php else : ?>
                        <?php echo esc_html( $cp ); ?>
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Image URL</label></th>
            <td>
                <input type="url" name="transactions[<?php echo $index; ?>][staged][image_url]" value="<?php echo esc_attr( em_get_field_value( $sd, 'image_url' ) ); ?>" style="width: 100%;" oninput="var p = document.getElementById('txn-img-<?php echo $index; ?>'); if(this.value) { p.src = this.value; p.style.display = 'block'; } else { p.style.display = 'none'; }">
            </td>
            <?php if ( $show_current_column ) : ?>
                <td class="<?php echo esc_attr( em_get_highlight_class( $sd['image_url'] ?? '', $cd['image_url'] ?? '' ) ); ?>">
                    <?php $ci = em_get_field_value( $cd, 'image_url' ); ?>
                    <?php if ( ! empty( $ci ) ) : ?>
                        <a href="<?php echo esc_url( $ci ); ?>" target="_blank"><?php echo esc_html( $ci ); ?></a>
                    <?php else : ?>
                        No image URL.
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
        <tr>
            <th scope="row"><label>Image Preview</label></th>
            <td>
                <?php if ( ! empty( $sd['image_url'] ) ) : ?>
                    <img id="txn-img-<?php echo $index; ?>" src="<?php echo esc_url( $sd['image_url'] ); ?>" alt="" style="max-width: 100%; height: auto; display: block; border: 1px solid #c3c4c7;" />
                <?php else : ?>
                    <img id="txn-img-<?php echo $index; ?>" src="" alt="" style="max-width: 100%; height: auto; display: none; border: 1px solid #c3c4c7;" />
                <?php endif; ?>
            </td>
            <?php if ( $show_current_column ) : ?>
                <td>
                    <?php if ( ! empty( $cd['image_url'] ) ) : ?>
                        <img src="<?php echo esc_url( $cd['image_url'] ); ?>" alt="" style="max-width: 100%; height: auto; display: block; border: 1px solid #c3c4c7;" />
                    <?php else : ?>
                        No image available.
                    <?php endif; ?>
                </td>
            <?php endif; ?>
        </tr>
    </tbody>
</table>
