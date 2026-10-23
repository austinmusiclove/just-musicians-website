<div class="flex items-center gap-1" x-data="{
    cardEnsembleSize: <?php echo clean_arr_for_doublequotes($args['ensemble_size'] ?? []); ?>,
}">

    <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/user-group.svg" />

    <span class="text-16 v"
        x-show="cardEnsembleSize.length > 0" x-cloak
        x-text="cardEnsembleSize.join(', ')"
    ></span>

    <p class="text-16 text-black/50" x-show="cardEnsembleSize.length == 0" x-cloak>Any Ensemble Size</p>

</div>
