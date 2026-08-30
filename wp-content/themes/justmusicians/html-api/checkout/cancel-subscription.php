<?php
/**
 * Cancels the logged-in user's active Talent Buyer Pro subscription.
 *
 */

$result = handle_cancel_subscription();

if (is_wp_error($result)) {
    $message = $result->get_error_message(); ?>
    <span x-init="$dispatch('error-toast', { 'message': '<?php echo $message; ?>' })"></span>
    <?php exit;
} ?>

<span x-init="redirect('<?php echo site_url('/subscriptions/?toast=cancelled'); ?>')"></span>
