<div id="<?php echo $args['id']; ?>" class="border-b border-black/20 mb-6 pb-6 last:mb-0 last:pb-0 last:border-b-0" hx-swap-oob="outerHTML">
    <h3 class="font-bold text-18 mb-3"><?php echo $args['title']; ?></h3>
    <div class="flex items-center gap-1 flex-wrap">
        <?php
            $tag_1_ref = get_checkbox_ref_string($args['input_name'], $args['tag_1']);
            $tag_2_ref = get_checkbox_ref_string($args['input_name'], $args['tag_2']);
            $tag_3_ref = get_checkbox_ref_string($args['input_name'], $args['tag_3']);
            $tag_4_ref = get_checkbox_ref_string($args['input_name'], $args['tag_4']);
        ?>
        <button type="button" class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20 hover:bg-yellow-light<?php if ($args['tag_1_selected']) { echo ' bg-yellow'; }?>"
            x-on:click="$refs.<?php echo $tag_1_ref; ?>.click()"><?php echo $args['tag_1']; ?></button>
        <button type="button" class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20 hover:bg-yellow-light<?php if ($args['tag_2_selected']) { echo ' bg-yellow'; }?>"
            x-on:click="$refs.<?php echo $tag_2_ref; ?>.click()"><?php echo $args['tag_2']; ?></button>
        <button type="button" class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20 hover:bg-yellow-light<?php if ($args['tag_3_selected']) { echo ' bg-yellow'; }?>"
            x-on:click="$refs.<?php echo $tag_3_ref; ?>.click()"><?php echo $args['tag_3']; ?></button>
        <button type="button" class="text-12 font-bold px-2 py-0.5 rounded-full border border-black/20 hover:bg-yellow-light<?php if ($args['tag_4_selected']) { echo ' bg-yellow'; }?>"
            x-on:click="$refs.<?php echo $tag_4_ref; ?>.click()"><?php echo $args['tag_4']; ?></button>
    </div>
    <button type="button" data-trigger="filter" class="underline mt-3 inline-block text-14" x-on:click="<?php echo $args['show_modal_var']; ?> = ! <?php echo $args['show_modal_var']; ?>">see all</button>
</div>
