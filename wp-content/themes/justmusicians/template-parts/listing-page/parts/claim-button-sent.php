<?php
$btn_text      = $args['btn_text']      ?? 'Claim Request Sent';
$success_toast = $args['success_toast'] ?? null;
$error_toast   = $args['error_toast']   ?? null;
?>

<button type="button" class="bg-yellow font-sun-motter w-full shadow-black-offset border-2 border-black px-2 py-2" disabled

    <?php if ($success_toast) { ?>
        x-init="$dispatch('success-toast', { 'message': '<?php echo $success_toast; ?>' })"
    <?php } else if ($error_toast) { ?>
        x-init="$dispatch('error-toast',   { 'message': '<?php echo $error_toast; ?>' })"
    <?php } ?>

><?php echo $btn_text; ?></button>
