<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
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
