<!-- Request proposal button -->
<?php
$btn_text      = $args['btn_text']      ?? 'Send';
$listing_var   = $args['listing_id_var'] ?? null;
$success_toast = $args['success_toast']  ?? null;
$error_toast   = $args['error_toast']    ?? null;
?>

<button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-3 rounded-sm font-sun-motter text-14 inline-block w-full"
    hx-target="this"
    hx-swap="outerHTML"
    hx-trigger="click"

    <?php // Get post url with alpine or regular var based on which one is passed in ?>
    <?php if ($listing_var) { ?>
        x-bind:hx-post="'<?php echo site_url('/wp-html/v1/events/' . $args['event_id'] . '/listings/'); ?>' + <?php echo $listing_var; ?> + '/request-proposal/'"
        x-bind:hx-indicator="'#request-proposal-btn-indicator-<?php echo $args['event_id']; ?>-' + <?php echo $listing_var; ?>"
    <?php } else { ?>
        hx-post="<?php echo site_url('/wp-html/v1/events/' . $args['event_id'] . '/listings/' . $args['listing_id'] . '/request-proposal'); ?>"
        hx-indicator="#request-proposal-btn-indicator-<?php echo $args['event_id'] . '-' . $args['listing_id']; ?>"
    <?php } ?>

    <?php // Handle toasts ?>
    <?php if ($success_toast) { ?>
        x-init="$dispatch('success-toast', { 'message': '<?php echo $success_toast; ?>' })"
    <?php } else if ($error_toast) { ?>
        x-init="$dispatch('error-toast',   { 'message': '<?php echo $error_toast; ?>' })"
    <?php } ?>
>
    <span class="flex justify-center"
        <?php if ($listing_var) { ?>
            x-bind:id="'request-proposal-btn-indicator-<?php echo $args['event_id']; ?>-' + <?php echo $listing_var; ?>"
        <?php } else { ?>
            id="request-proposal-btn-indicator-<?php echo $args['event_id'] . '-' . $args['listing_id']; ?>"
        <?php } ?>
    >
        <span class="htmx-indicator-component-block-replace"><?php echo $btn_text; ?></span>
        <span class="htmx-indicator-component-block">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
        </span>
    </span>
</button>
