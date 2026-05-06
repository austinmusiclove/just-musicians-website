<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <?php if ( ! empty( $error_msg ) ) : ?>
        <div class="notice notice-error inline" style="margin: 20px 0;">
            <p><strong>Error:</strong> <?php echo esc_html( $error_msg ); ?></p>
        </div>
    <?php endif; ?>

    <?php include plugin_dir_path( __FILE__ ) . 'components/events/events-table.php'; ?>
</div>
