<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get URLs from staged data (available via $staged variable)
$ticket_url = $staged['ticket_url'] ?? '';
$event_page_url = $staged['event_page_url'] ?? '';
$event_list_screenshot = $staged['event_list_screenshot'] ?? '';
$event_page_screenshot = $staged['event_page_screenshot'] ?? '';
?>
<div class="em-column-screenshot" style="flex: 1; min-width: 300px; max-width: 50%;">
    <div style="background: #fff; border: 1px solid #c3c4c7; padding: 20px;">
        <?php if ( ! empty( $event_list_screenshot ) ) : ?>
            <h2>Event List Screenshot</h2>
            <div class="em-screenshot-container" style="max-height: 100vh; overflow-y: auto; border: 1px solid #c3c4c7; background: #f0f0f1; margin-bottom: 20px;">
                <img src="<?php echo esc_url( $event_list_screenshot ); ?>" alt="Event List Screenshot" style="display: block; max-width: 100%; height: auto;" />
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $event_page_screenshot ) ) : ?>
            <h2>Event Page Screenshot</h2>
            <div class="em-screenshot-container" style="max-height: 100vh; overflow-y: auto; border: 1px solid #c3c4c7; background: #f0f0f1; margin-bottom: 20px;">
                <img src="<?php echo esc_url( $event_page_screenshot ); ?>" alt="Event Page Screenshot" style="display: block; max-width: 100%; height: auto;" />
            </div>
        <?php endif; ?>

        <?php if ( empty( $event_list_screenshot ) && empty( $event_page_screenshot ) ) : ?>
            <p>No screenshots available.</p>
        <?php endif; ?>

        <?php if ( ! empty( $event_page_url ) ) : ?>
            <h2 style="margin-top: 30px;">Event Page Preview</h2>
            <div class="em-eventpage-iframe-container" style="border: 1px solid #c3c4c7; background: #f0f0f1;">
                <iframe src="<?php echo esc_url( $event_page_url ); ?>" style="width: 100%; height: 500px; border: none;"></iframe>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $ticket_url ) ) : ?>
            <h2 style="margin-top: 30px;">Ticket Page Preview</h2>
            <div class="em-ticket-iframe-container" style="border: 1px solid #c3c4c7; background: #f0f0f1;">
                <iframe src="<?php echo esc_url( $ticket_url ); ?>" style="width: 100%; height: 500px; border: none;"></iframe>
            </div>
        <?php endif; ?>
    </div>
</div>
