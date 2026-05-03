<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<h2 style="margin-top: 30px;">Meta Data</h2>
<table class="form-table">
    <tr>
        <th scope="row"><label>ID</label></th>
        <td><?php echo esc_html( isset( $transaction['id'] ) ? $transaction['id'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>Current Data ID</label></th>
        <td><?php echo esc_html( isset( $transaction['current_data_id'] ) ? $transaction['current_data_id'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>Staged Data ID</label></th>
        <td><?php echo esc_html( isset( $transaction['staged_data_id'] ) ? $transaction['staged_data_id'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>Transaction Type</label></th>
        <td><?php echo esc_html( isset( $transaction['transaction_type'] ) ? $transaction['transaction_type'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>Data Index</label></th>
        <td><?php echo esc_html( isset( $transaction['data_index'] ) ? $transaction['data_index'] : '-' ); ?></td>
    </tr>
    <tr>
        <th scope="row"><label>Scrape URL</label></th>
        <td>
            <?php if ( ! empty( $transaction['scrape_url'] ) ) : ?>
                <a href="<?php echo esc_url( $transaction['scrape_url'] ); ?>" target="_blank"><?php echo esc_html( $transaction['scrape_url'] ); ?></a>
            <?php else : ?>
                -
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th scope="row"><label>Created At</label></th>
        <td>
            <?php if ( ! empty( $transaction['created_at'] ) ) : ?>
                <?php echo esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $transaction['created_at'] ) ) ); ?>
            <?php else : ?>
                -
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <th scope="row"><label>Updated At</label></th>
        <td>
            <?php if ( ! empty( $transaction['updated_at'] ) ) : ?>
                <?php echo esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $transaction['updated_at'] ) ) ); ?>
            <?php else : ?>
                -
            <?php endif; ?>
        </td>
    </tr>
</table>
