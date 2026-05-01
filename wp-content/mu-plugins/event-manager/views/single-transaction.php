<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="wrap">
    <a href="<?php echo esc_url( remove_query_arg( array( 'action', 'id' ) ) ); ?>" class="button" style="margin-bottom: 10px;">
        &larr; Back to Transactions
    </a>
    <h1><?php echo esc_html( get_admin_page_title() ); ?> &mdash; Transaction Details</h1>

    <?php if ( ! empty( $error_msg ) ) : ?>
        <div class="notice notice-error inline" style="margin: 10px 0;">
            <p><strong>Error:</strong> <?php echo esc_html( $error_msg ); ?></p>
        </div>
    <?php elseif ( ! empty( $transaction ) ) : ?>
        <div class="em-transaction-columns" style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
            <div class="em-column-screenshot" style="flex: 1; min-width: 300px; max-width: 50%;">
                <div style="background: #fff; border: 1px solid #c3c4c7; padding: 20px;">
                    <h2>Screenshot</h2>
                    <?php if ( ! empty( $transaction['screenshot'] ) ) : ?>
                        <div class="em-screenshot-container" style="max-height: 100vh; overflow-y: auto; border: 1px solid #c3c4c7; background: #f0f0f1;">
                            <img src="<?php echo esc_url( $transaction['screenshot'] ); ?>" alt="Transaction Screenshot" style="display: block; max-width: 100%; height: auto;" />
                        </div>
                    <?php else : ?>
                        <p>No screenshot available.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="em-column-data" style="flex: 1; min-width: 300px;">
                <div style="background: #fff; border: 1px solid #c3c4c7; padding: 20px;">
                    <style>
                        .em-transaction-columns .form-table td,
                        .em-transaction-columns .form-table th {
                            padding: 6px 10px;
                        }
                    </style>
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

                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #c3c4c7;">
                        <button type="button" class="button button-primary" id="em-approve-transaction">Approve Transaction</button>
                        <button type="button" class="button button-secondary" id="em-reject-transaction" style="margin-left: 8px;">Reject Transaction</button>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="notice notice-warning inline" style="margin: 10px 0;">
            <p><strong>Not Found:</strong> No transaction data available.</p>
        </div>
    <?php endif; ?>
</div>
