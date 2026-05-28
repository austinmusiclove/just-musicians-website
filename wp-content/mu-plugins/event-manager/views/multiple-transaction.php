<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap">
    <a href="<?php echo esc_url( admin_url( 'admin.php?page=event-manager&tab=bulk-review' ) ); ?>" class="button" style="margin-bottom: 10px;">
        &larr; Back to Bulk Review
    </a>
    <h1><?php echo esc_html( get_admin_page_title() ); ?> &mdash; Multiple Transaction Details</h1>

    <?php if ( ! empty( $error_msg ) ) : ?>
        <div class="notice notice-error inline" style="margin: 10px 0;">
            <p><strong>Error:</strong> <?php echo esc_html( $error_msg ); ?></p>
        </div>
    <?php endif; ?>

    <?php if ( ! empty( $success_msg ) ) : ?>
        <div class="notice notice-success inline" style="margin: 10px 0;">
            <p><strong>Success:</strong> <?php echo esc_html( $success_msg ); ?></p>
        </div>
    <?php endif; ?>

    <?php if ( ! empty( $transaction ) ) : ?>
        <style>
            .current-data-changed {
                background-color: #fff3cd;
                border-radius: 3px;
                padding: 5px;
            }
            .em-multi-transaction-columns .form-table td,
            .em-multi-transaction-columns .form-table th {
                padding: 6px 10px;
            }
        </style>
        <div class="em-multi-transaction-columns" style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px; align-items: flex-start;">
            <div style="flex: 1; min-width: 300px; max-width: 50%; position: sticky; top: 20px; align-self: flex-start;">
                <div style="background: #fff; border: 1px solid #c3c4c7; padding: 20px;">
                    <?php if ( ! empty( $event_list_screenshot ) ) : ?>
                        <h2>Event List Screenshot</h2>
                        <div style="max-height: 100vh; overflow-y: auto; border: 1px solid #c3c4c7; background: #f0f0f1;">
                            <img src="<?php echo esc_url( $event_list_screenshot ); ?>" alt="Event List Screenshot" style="display: block; max-width: 100%; height: auto;" />
                        </div>
                    <?php else : ?>
                        <p>No event list screenshot available.</p>
                    <?php endif; ?>
                    <?php include plugin_dir_path( __FILE__ ) . 'components/single-transaction/venue-future-events-table.php'; ?>
                </div>
            </div>

            <div style="flex: 1; min-width: 300px; max-height: calc(100vh - 180px); overflow-y: auto;">
                <form method="post" action="">
                    <?php wp_nonce_field( 'em_bulk_approve_' . $transaction['id'] ); ?>
                    <?php foreach ( $sub_transactions as $index => $sub_txn ) : ?>
                        <?php $sd = $sub_txn['staged_data'] ?? array(); ?>
                        <?php $cd = $sub_txn['current_data'] ?? array(); ?>
                        <div style="background: #fff; border: 1px solid #c3c4c7; padding: 20px; margin-bottom: 20px;">
                            <h2>Transaction <?php echo $index + 1; ?></h2>
                            <?php include plugin_dir_path( __FILE__ ) . 'components/multiple-transaction/sub-transaction-data-table.php'; ?>
                        </div>
                    <?php endforeach; ?>

                    <div style="position: sticky; bottom: 0; background: #fff; padding: 16px 20px; border-top: 1px solid #c3c4c7; display: flex; gap: 10px;">
                        <button type="submit" name="em_bulk_approve_all" value="1" class="button button-primary">
                            Approve All
                        </button>
                        <button type="button" class="button">
                            Reject All
                        </button>
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
