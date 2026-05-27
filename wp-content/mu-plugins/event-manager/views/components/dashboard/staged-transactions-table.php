<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<?php if ( empty( $error_msg ) ) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var selectAll = document.getElementById('cb-select-all');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    var checked = this.checked;
                    document.querySelectorAll('.em-row-cb').forEach(function(cb) {
                        cb.checked = checked;
                    });
                });
            }
        });
    </script>
    <table class="wp-list-table widefat fixed striped table-view-list">
        <thead>
            <tr>
                <th scope="col" style="width: 30px;">
                    <input type="checkbox" id="cb-select-all">
                </th>
                <th scope="col" style="width: 60px;">ID</th>
                <th scope="col" style="width: 80px;">Venue</th>
                <th scope="col">Event Title</th>
                <th scope="col" style="width: 120px;">Transaction Type</th>
                <th scope="col" style="width: 120px;">Date</th>
                <th scope="col">Scrape URL</th>
                <th scope="col" style="width: 140px;">Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( ! empty( $staged_transactions ) && is_array( $staged_transactions ) ) : ?>
                <?php foreach ( $staged_transactions as $transaction ) : ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="em-row-cb" name="em_transactions[]" value="<?php echo esc_attr( isset( $transaction['id'] ) ? $transaction['id'] : '' ); ?>">
                        </td>
                        <td><?php
                            $id = isset( $transaction['id'] ) ? $transaction['id'] : '';
                            if ( ! empty( $id ) ) {
                                $link = add_query_arg( array( 'action' => 'view', 'id' => $id ) );
                                echo '<a href="' . esc_url( $link ) . '">' . esc_html( $id ) . '</a>';
                            } else {
                                echo '-';
                            }
                        ?></td>
                        <td>
                            <?php
                                $venue = ! empty( $transaction['current_venue_name'] ) ? $transaction['current_venue_name'] : ( isset( $transaction['staged_venue_name'] ) ? $transaction['staged_venue_name'] : '-' );
                                echo esc_html( $venue );
                            ?>
                        </td>
                        <td>
                            <?php
                                $event_id = isset( $transaction['id'] ) ? esc_attr( $transaction['id'] ) : '';
                                $current_title = ! empty( $transaction['current_event_title'] ) ? $transaction['current_event_title'] : '';
                                $event_title = ! empty( $current_title ) ? $current_title : ( isset( $transaction['staged_event_title'] ) ? $transaction['staged_event_title'] : '-' );
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
                            <?php
                                $date = ! empty( $transaction['current_start_date'] ) ? $transaction['current_start_date'] : ( isset( $transaction['staged_start_date'] ) && ! empty( $transaction['staged_start_date'] ) ? $transaction['staged_start_date'] : '-' );
                                echo esc_html( $date );
                            ?>
                        </td>
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
                    <td colspan="8" style="text-align: center; padding: 20px;">No staged transactions found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th scope="col"></th>
                <th scope="col">ID</th>
                <th scope="col">Venue</th>
                <th scope="col">Event Title</th>
                <th scope="col">Transaction Type</th>
                <th scope="col">Date</th>
                <th scope="col">Scrape URL</th>
                <th scope="col">Created At</th>
            </tr>
        </tfoot>
    </table>
<?php endif; ?>
