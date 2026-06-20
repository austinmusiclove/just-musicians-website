<div>
    <h3 class="font-bold text-16 mb-2">Details</h3>
    <?php if ($args['details']) { ?>
        <p class="text-16"><?php echo esc_html($args['details']); ?></p>
    <?php } else { ?>
        <p class="text-16 text-black/50">Not specified</p>
    <?php } ?>
</div>
