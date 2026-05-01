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
                <div class="card">
                    <h2>Screenshot</h2>
                    <?php if ( ! empty( $transaction['screenshot'] ) ) : ?>
                        <div class="em-screenshot-container" style="max-height: 70vh; overflow-y: auto; border: 1px solid #c3c4c7; background: #f0f0f1;">
                            <img src="<?php echo esc_url( $transaction['screenshot'] ); ?>" alt="Transaction Screenshot" style="display: block; max-width: 100%; height: auto;" />
                        </div>
                    <?php else : ?>
                        <p>No screenshot available.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="em-column-data" style="flex: 1; min-width: 300px;">
                <div class="card">
                    <h2>Event Data</h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label>ID</label></th>
                            <td><?php echo esc_html( isset( $transaction['id'] ) ? $transaction['id'] : '-' ); ?></td>
                        </tr>
                        <?php foreach ( $transaction as $key => $value ) : ?>
                            <?php if ( 'id' === $key || 'screenshot' === $key ) continue; ?>
                            <tr>
                                <th scope="row"><label><?php echo esc_html( ucwords( str_replace( '_', ' ', $key ) ) ); ?></label></th>
                                <td>
                                    <?php if ( is_array( $value ) || is_object( $value ) ) : ?>
                                        <pre><?php echo esc_html( wp_json_encode( $value, JSON_PRETTY_PRINT ) ); ?></pre>
                                    <?php else : ?>
                                        <?php echo esc_html( $value ); ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="notice notice-warning inline" style="margin: 10px 0;">
            <p><strong>Not Found:</strong> No transaction data available.</p>
        </div>
    <?php endif; ?>
</div>
