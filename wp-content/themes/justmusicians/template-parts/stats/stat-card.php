<div class="rounded-xl p-5 border border-black/20">
    <div class="mb-3 flex items-center justify-between">
        <span class="text-18">
            <?php echo $args['title']; ?>
        </span>

        <span>
            <img class="h-6 opacity-80" src="<?php echo get_template_directory_uri() . "/lib/images/icons/{$args['icon']}"; ?>" />
        </span>
    </div>

    <div class="mb-1 text-36 font-bold leading-tight text-navy">
        <?php echo $args['stat']; ?>
    </div>

    <div class="text-14 opacity-50">
        <?php echo $args['sub_text']; ?>
    </div>

</div>

