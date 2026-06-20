<div class="flex items-start gap-1">

    <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/user-group.svg" />

    <span class="text-16 v"
        x-show="ensemble_size.length > 0" x-cloak
        x-text="ensemble_size.join(', ')"
    ></span>

    <p class="text-16 text-black/50" x-show="ensemble_size.length == 0" x-cloak>Any Ensemble Size</p>

</div>
