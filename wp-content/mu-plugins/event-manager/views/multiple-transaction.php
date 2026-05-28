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
                        <?php $txn_status = $sub_txn['status'] ?? ''; ?>
                        <?php $is_disabled = in_array( $txn_status, array( 'approved', 'rejected' ) ); ?>
                        <div style="background: #fff; border: 1px solid #c3c4c7; padding: 20px; margin-bottom: 20px;">
                            <h2 style="margin-top: 0; margin-bottom: 4px">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="checkbox" class="em-txn-checkbox" name="selected_indices[]" value="<?php echo $index; ?>" <?php echo $is_disabled ? 'disabled' : ''; ?>>
                                    Transaction <?php echo $index + 1; ?>
                                </label>
                            </h2>
                            <?php if ( $txn_status ) : ?>
                                <span style="display: block; font-size: 12px; color: #666; margin: 2px 0 10px 32px;">Status: <?php echo esc_html( $txn_status ); ?></span>
                            <?php endif; ?>
                            <input type="hidden" name="transactions[<?php echo $index; ?>][staged_transaction_id]" value="<?php echo esc_attr( $sub_txn['staged_transaction_id'] ?? $sub_txn['id'] ?? '' ); ?>">
                            <?php include plugin_dir_path( __FILE__ ) . 'components/multiple-transaction/sub-transaction-data-table.php'; ?>
                        </div>
                    <?php endforeach; ?>

                    <div style="position: sticky; bottom: 0; background: #fff; padding: 16px 20px; border-top: 1px solid #c3c4c7; display: flex; gap: 10px; align-items: center;">
                        <label style="display: flex; align-items: center; gap: 4px; cursor: pointer; font-size: 13px; margin-right: auto;">
                            <input type="checkbox" id="em-select-all"> Select All
                        </label>
                        <button type="submit" name="em_bulk_approve_selected" value="1" class="button button-primary">
                            Approve Selected
                        </button>
                        <button type="submit" name="em_bulk_reject_selected" value="1" class="button">
                            Reject Selected
                        </button>
                    </div>
                    <script>
                    document.getElementById('em-select-all').addEventListener('change', function() {
                        var checkboxes = document.querySelectorAll('.em-txn-checkbox');
                        for (var i = 0; i < checkboxes.length; i++) {
                            if (!checkboxes[i].disabled) {
                                checkboxes[i].checked = this.checked;
                            }
                        }
                    });
                    </script>
                </form>
            </div>
        </div>
    <?php else : ?>
        <div class="notice notice-warning inline" style="margin: 10px 0;">
            <p><strong>Not Found:</strong> No transaction data available.</p>
        </div>
    <?php endif; ?>
</div>
