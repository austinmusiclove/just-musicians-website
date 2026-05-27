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
        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-top: 20px;">
            <div style="flex: 1; min-width: 300px; max-width: 50%;">
                <div style="background: #fff; border: 1px solid #c3c4c7; padding: 20px;">
                    <?php if ( ! empty( $event_list_screenshot ) ) : ?>
                        <h2>Event List Screenshot</h2>
                        <div style="max-height: 100vh; overflow-y: auto; border: 1px solid #c3c4c7; background: #f0f0f1;">
                            <img src="<?php echo esc_url( $event_list_screenshot ); ?>" alt="Event List Screenshot" style="display: block; max-width: 100%; height: auto;" />
                        </div>
                    <?php else : ?>
                        <p>No event list screenshot available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div style="flex: 1; min-width: 300px;">
                <form method="post" action="">
                    <?php wp_nonce_field( 'em_bulk_approve_' . $transaction['id'] ); ?>
                    <div style="background: #fff; border: 1px solid #c3c4c7; padding: 20px;">
                        <?php include plugin_dir_path( __FILE__ ) . 'components/multiple-transaction/events-summary-table.php'; ?>

                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #c3c4c7;">
                            <button type="submit" name="em_bulk_approve_all" value="1" class="button button-primary">
                                Bulk Approve All Events
                            </button>
                        </div>
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
