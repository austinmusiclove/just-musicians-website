<div id="<?php echo $args['id']; ?>" class="border-b border-black/20 mb-6 pb-6 last:mb-0 last:pb-0 last:border-b-0" hx-swap-oob="outerHTML">
    <h3 class="font-bold text-18 mb-3"><?php echo $args['title']; ?></h3>
    <div class="flex items-center gap-1 flex-wrap">


        <!-- Add all selected tags -->
        <?php foreach($args['tags'] as $tag) {
            $tag_ref = get_checkbox_ref_string($args['input_name'], $tag); ?>
            <button type="button" class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20 hover:bg-yellow-light bg-yellow"
                x-on:click="$refs.<?php echo $tag_ref; ?>.click()">
                <?php echo $tag; ?>
            </button>
        <?php }


        // Add default options
        if (count($args['tags']) < count($args['default_tags'])) {
            $num_default_tags_to_add = max(0, count($args['default_tags']) - count($args['tags']));
            $default_tags = array_slice(array_diff($args['default_tags'], $args['tags']), 0, $num_default_tags_to_add);
            foreach($default_tags as $tag) {
                $tag_ref = get_checkbox_ref_string($args['input_name'], $tag); ?>
                <button type="button" class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20 hover:bg-yellow-light"
                    x-on:click="$refs.<?php echo $tag_ref; ?>.click()">
                    <?php echo $tag; ?>
                </button>
            <?php }
        } ?>


    </div>
</div>
