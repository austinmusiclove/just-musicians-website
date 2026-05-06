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
        <style>
            .em-transaction-columns .form-table td,
            .em-transaction-columns .form-table th {
                padding: 6px 10px;
            }
        </style>
        <div class="em-transaction-columns" style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
            <?php include plugin_dir_path( __FILE__ ) . 'components/single-transaction/screenshot.php'; ?>

            <div class="em-column-data" style="flex: 1; min-width: 300px;">
                <form method="post" action="">
                    <?php wp_nonce_field( 'em_approve_' . $transaction['id'] ); ?>
                    <div style="background: #fff; border: 1px solid #c3c4c7; padding: 20px;">
                        <?php include plugin_dir_path( __FILE__ ) . 'components/single-transaction/staged-event-data-table.php'; ?>
                        <?php include plugin_dir_path( __FILE__ ) . 'components/single-transaction/staged-transaction-meta-data-table.php'; ?>
                        <?php include plugin_dir_path( __FILE__ ) . 'components/single-transaction/action-buttons.php'; ?>
                    </div>
                </form>
            </div>
        </div>
    <?php else : ?>
        <div class="notice notice-warning inline" style="margin: 10px 0;">
            <p><strong>Not Found:</strong> No transaction data available.</p>
        </div>
    <?php endif; ?>
</div>
