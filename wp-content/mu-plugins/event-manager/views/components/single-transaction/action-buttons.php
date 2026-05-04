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
        <button type="submit" name="em_reject" value="1" class="button button-secondary" id="em-reject-transaction" style="margin-left: 8px;">
            Reject Transaction
        </button>
    </div>
    <div style="margin-left: auto;">
        <button type="button" class="button button-secondary" id="em-next-transaction">
            Next &rarr;
        </button>
    </div>
</div>

<?php if ( ! empty( $approve_result ) ) : ?>
    <div class="notice notice-<?php echo esc_attr( $approve_result['type'] ); ?> inline" style="margin: 10px 0;">
        <p><strong><?php echo $approve_result['type'] === 'error' ? 'Error:' : 'Success:'; ?></strong>
           <?php echo esc_html( $approve_result['message'] ); ?></p>
        <?php if ( ! empty( $approve_result['body'] ) ) : ?>
            <pre style="margin-top: 10px; padding: 10px; background: #f0f0f1; border: 1px solid #c3c4c7; overflow: auto; max-height: 300px;">
                <?php echo esc_html( $approve_result['body'] ); ?>
            </pre>
        <?php endif; ?>
    </div>
<?php endif; ?>
