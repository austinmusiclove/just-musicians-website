<div class="flex items-start gap-1">

    <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/user-group.svg" />

    <span class="text-16 v"
        x-show="ensembleSize.length > 0" x-cloak
        x-text="ensembleSize.join(', ')"
    ></span>

    <p class="text-16 text-black/50" x-show="ensembleSize.length == 0" x-cloak>Any Ensemble Size</p>

</div>
