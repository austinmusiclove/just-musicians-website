<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <p>Welcome to the Event Manager. Use this page to manage event operations.</p>
    
    <div class="card" style="max-width: 400px; margin-top: 20px;">
        <h2>Staged Transactions</h2>
        <?php if ( ! empty( $error_msg ) ) : ?>
            <div class="notice notice-error inline" style="margin: 10px 0;">
                <p><strong>Error:</strong> <?php echo esc_html( $error_msg ); ?></p>
            </div>
        <?php else : ?>
            <p style="font-size: 1.5em; font-weight: bold; color: #2271b1;">
                Total: <?php echo esc_html( $staged_count ); ?>
            </p>
        <?php endif; ?>
    </div>
</div>
