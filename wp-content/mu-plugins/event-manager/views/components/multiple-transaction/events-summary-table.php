<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<h2>Events</h2>
<table class="wp-list-table widefat fixed striped table-view-list" style="margin-bottom: 20px;">
    <thead>
        <tr>
            <th style="width: 40px;">#</th>
            <th>Title</th>
            <th style="width: 80px;">Image</th>
            <th style="width: 110px;">Start Date</th>
            <th style="width: 90px;">Start Time</th>
            <th>Event Page URL</th>
        </tr>
    </thead>
    <tbody>
        <?php if ( ! empty( $sub_transactions ) && is_array( $sub_transactions ) ) : ?>
            <?php $index = 1; ?>
            <?php foreach ( $sub_transactions as $sub_txn ) : ?>
                <?php $sd = isset( $sub_txn['staged_data'] ) ? $sub_txn['staged_data'] : array(); ?>
                <tr>
                    <td><?php echo $index++; ?></td>
                    <td><strong><?php echo esc_html( isset( $sd['title'] ) ? $sd['title'] : '-' ); ?></strong></td>
                    <td>
                        <?php if ( ! empty( $sd['image_url'] ) ) : ?>
                            <img src="<?php echo esc_url( $sd['image_url'] ); ?>" alt="" style="max-width: 60px; max-height: 60px; border: 1px solid #c3c4c7;" />
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?php echo esc_html( isset( $sd['start_date'] ) ? $sd['start_date'] : '-' ); ?></td>
                    <td><?php echo esc_html( isset( $sd['start_time'] ) ? $sd['start_time'] : '-' ); ?></td>
                    <td>
                        <?php if ( ! empty( $sd['event_page_url'] ) ) : ?>
                            <a href="<?php echo esc_url( $sd['event_page_url'] ); ?>" target="_blank"><?php echo esc_html( $sd['event_page_url'] ); ?></a>
                        <?php else : ?>
                            -
                        <?php endif; ?>
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
            <th>#</th>
            <th>Title</th>
            <th>Image</th>
            <th>Start Date</th>
            <th>Start Time</th>
            <th>Event Page URL</th>
        </tr>
    </tfoot>
</table>
