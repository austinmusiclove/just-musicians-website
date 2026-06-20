<div class="flex items-start gap-1">
    <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/user-group.svg" />
    <?php if ($args['ensemble_size']) { ?>
        <span class="text-16 v"><?php echo implode(', ', $args['ensemble_size']); ?></span>
    <?php } else { ?>
        <span class="text-16 text-black/50">Any Ensemble Size</span>
    <?php } ?>
</div>
