<?php

if (!empty($args['ensemble_size'])) { ?>

    <div class="flex items-center gap-1">
        <img style="height: .9rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/user-group.svg" />
        <span class="text-14 v"><?php echo implode(', ', $args['ensemble_size']); ?></span>
    </div>

<?php } else if (isset($args['ensemble_size_var'])) {
    $ensemble_size_var = $args['ensemble_size_var'] ?? 'cardEnsembleSize';
    $show_any_if_empty = $args['show_any_if_empty'] ?? false;
?>

    <div class="flex items-center gap-1" x-show="<?php echo $ensemble_size_var; ?> && <?php echo $ensemble_size_var; ?>.length > 0" x-claok>
        <img style="height: .9rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/user-group.svg" />

        <span class="text-14 v"
            x-show="<?php echo $ensemble_size_var; ?>.length > 0" x-cloak
            x-text="<?php echo $ensemble_size_var; ?>.join(', ')"
        ></span>

        <?php if ($show_any_if_empty) { ?>
            <p class="text-14 text-black/50" x-show="<?php echo $ensemble_size_var; ?>.length == 0" x-cloak>Any Ensemble Size</p>
        <?php } ?>
    </div>

<?php } ?>
