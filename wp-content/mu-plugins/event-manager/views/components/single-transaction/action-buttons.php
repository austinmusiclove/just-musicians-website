<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #c3c4c7; display: flex; align-items: center;">
    <div>
        <button type="submit" name="em_approve" value="1" class="button button-primary" id="em-approve-transaction">
            Approve Transaction
        </button>
        <button type="submit" name="em_update" value="1" class="button button-secondary" id="em-update-transaction" style="margin-left: 8px;">
            Update Transaction
        </button>
        <button type="submit" name="em_reject" value="1" class="button button-secondary" id="em-reject-transaction" style="margin-left: 8px;">
            Reject Transaction
        </button>
    </div>
    <div style="margin-left: auto;">
        <?php if ( ! empty( $transaction['next_transaction_id'] ) ) : ?>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=event-manager&action=view&id=' . urlencode( $transaction['next_transaction_id'] ) ) ); ?>" class="button button-secondary" id="em-next-transaction">
                Next &rarr;
            </a>
        <?php endif; ?>
    </div>
</div>
