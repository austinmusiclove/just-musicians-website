<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<?php if ( empty( $error_msg ) ) : ?>
    <div style="margin-top: 20px;">
        <table class="wp-list-table widefat fixed striped table-view-list">
            <thead>
                <tr>
                    <th scope="col" style="width: 80px;">ID</th>
                    <th scope="col" style="width: 120px;">Status</th>
                    <th scope="col">Event Title</th>
                    <th scope="col" style="width: 150px;">Transaction Type</th>
                    <th scope="col">Scrape URL</th>
                    <th scope="col" style="width: 180px;">Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php if ( ! empty( $staged_transactions ) && is_array( $staged_transactions ) ) : ?>
                    <?php foreach ( $staged_transactions as $transaction ) : ?>
                        <tr>
                            <td><?php echo esc_html( isset( $transaction['id'] ) ? $transaction['id'] : '-' ); ?></td>
                            <td>
                                <?php
                                    $status = isset( $transaction['status'] ) ? $transaction['status'] : '-';
                                    echo esc_html( $status );
                                ?>
                            </td>
                            <td>
                                <?php
                                    $event_id = isset( $transaction['id'] ) ? esc_attr( $transaction['id'] ) : '';
                                    $event_title = isset( $transaction['staged_event_title'] ) ? $transaction['staged_event_title'] : '-';
                                    if ( ! empty( $event_id ) ) {
                                        $link = add_query_arg( array( 'action' => 'view', 'id' => $event_id ) );
                                        echo '<a href="' . esc_url( $link ) . '"><strong>' . esc_html( $event_title ) . '</strong></a>';
                                    } else {
                                        echo '<strong>' . esc_html( $event_title ) . '</strong>';
                                    }
                                ?>
                            </td>
                            <td><?php echo esc_html( isset( $transaction['transaction_type'] ) ? $transaction['transaction_type'] : '-' ); ?></td>
                            <td>
                                <?php if ( ! empty( $transaction['scrape_url'] ) ) : ?>
                                    <a href="<?php echo esc_url( $transaction['scrape_url'] ); ?>" target="_blank">
                                        <?php echo esc_html( $transaction['scrape_url'] ); ?>
                                    </a>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    if ( ! empty( $transaction['created_at'] ) ) {
                                        $timestamp = strtotime( $transaction['created_at'] );
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
                        <td colspan="6" style="text-align: center; padding: 20px;">No staged transactions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Status</th>
                    <th scope="col">Event Title</th>
                    <th scope="col">Transaction Type</th>
                    <th scope="col">Scrape URL</th>
                    <th scope="col">Created At</th>
                </tr>
            </tfoot>
        </table>
    </div>
<?php endif; ?>
