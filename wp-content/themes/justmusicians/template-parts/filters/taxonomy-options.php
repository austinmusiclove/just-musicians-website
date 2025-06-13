<div class="my-4" x-data='{
    searchQuery: "",
    showOption(option) { return this.searchQuery === "" || option.toLowerCase().includes(this.searchQuery.toLowerCase()); },
}'>
    <?php if (!empty($args['title'])) { ?><h2 class="font-bold text-22"><?php echo $args['title']; ?></h2><?php } ?>
    <input type='text' class="my-6" placeholder="search..." x-model="searchQuery"></input>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 gap-y-2 gap-x-10 custom-checkbox overflow-scroll max-h-[500px] md:max-h-[240px]">
    <!-- This hidden input insures that this input data gets sent as [""] if no checkboxes are set instead of omitting it from the post body -->
    <input type="hidden" name="<?php echo $args['input_name']; ?>[]" >
    <?php
        if (!empty($args['terms']) && !is_wp_error($args['terms'])) {
            foreach ($args['terms'] as $term) {
                echo get_template_part('template-parts/filters/elements/checkbox', '', [
                    'label' => $term,
                    'value' => $term,
                    'name' => $args['input_name'],
                    'x-model' => $args['input_x_model'],
                    'x-ref' => get_checkbox_ref_string($args['input_name'], $term),
                    'x-show' => "showOption('" . str_replace(["'", '"'], '', $term) . "')",
                    'x-disabled' => (!empty($args['max_options'])) ? $args['input_x_model'] . ".length >= " . $args['max_options'] . " && !" . $args['input_x_model'] . ".map((term) => term.replace(/'/g, '')).includes('" . str_replace(["'", '"'], '', $term) . "')" : null,
                    'is_array' => true,
                    'checked' => false,
                ]);
            }
        }
    ?>
    </div>
</div>
