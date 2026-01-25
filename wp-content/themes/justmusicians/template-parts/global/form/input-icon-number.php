<div class="grow relative">
    <div class="flex flex-row">
        <label class="mb-1 mr-2 inline-block" for="<?php echo $args['input_name']; ?>">
            <?php echo $args['title']; ?>
            <?php if ($args['required']) { ?><span class="text-red">*</span><?php } ?>
        </label><br>
        <?php if (!empty($args['tooltip'])) { echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => $args['tooltip'], ]); } ?>
    </div>
    <div class="relative">
        <img class="h-5 absolute bottom-2.5 left-3 opacity-60" src="<?php echo get_template_directory_uri() . '/lib/images/icons/' . $args['icon_filename']; ?>" />
        <input class="has-icon" type="number"
            id="<?php echo $args['input_name']; ?>"
            name="<?php echo $args['input_name']; ?>"
            placeholder="<?php echo $args['placeholder']; ?>"
            <?php if (!empty($args['min'])) { echo 'min="' . $args['min'] . '"'; } ?>
            <?php if (!empty($args['max'])) { echo 'max="' . $args['max'] . '"'; } ?>
        >
        <?php if (!empty($args['unit_sufix'])) { ?><span class="h-5 absolute bottom-3 right-9 opacity-60"><?php echo $args['unit_sufix']; ?></span><?php } ?>
    </div>
</div>
