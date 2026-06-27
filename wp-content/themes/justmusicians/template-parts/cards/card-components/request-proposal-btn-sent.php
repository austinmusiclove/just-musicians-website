<!-- Request proposal button (already sent state) -->
<?php
$success_toast = $args['success_toast'] ?? null;
$error_toast   = $args['error_toast']   ?? null;
?>

<button type="button" class="bg-navy px-3 py-3 rounded-sm font-sun-motter text-14 text-white inline-block w-full" disabled

    <?php // Handle toasts ?>
    <?php if ($success_toast) { ?>
        x-init="$dispatch('success-toast', { 'message': '<?php echo $success_toast; ?>' })"
    <?php } else if ($error_toast) { ?>
        x-init="$dispatch('error-toast',   { 'message': '<?php echo $error_toast; ?>' })"
    <?php } ?>

>Sent</button>
