<fieldgroup class="has-border p-0">
    <div class="hidden">
        <input type="hidden" name="<?php echo $args['input_name']; ?>[]" >
        <?php foreach ($args['options'] as $option) {
            echo get_template_part('template-parts/global/form/checkbox', '', [
                'label'    => $option,
                'value'    => $option,
                'name'     => $args['input_name'],
                'x-model'  => $args['x-model'],
                'is_array' => true,
            ]);
        } ?>
    </div>
    <label id="<?php echo $args['id']; ?>" class="block bg-yellow-10 w-full p-2 flex items-center gap-1 rounded-t-sm">
        <span class="font-bold"><?php echo $args['label']; ?> <span class="text-14 font-normal">(select all that apply)</span></span>
        <?php if (isset($args['tooltip'])) { echo get_template_part('template-parts/global/tooltips/tooltip', '', [ 'tooltip' => $args['tooltip'] ]); } ?>
    </label>
    <div class="p-2 flex gap-1 items-start flex-wrap h-20" x-data="{
        tagOptions: <?php echo clean_arr_for_doublequotes($args['options']); ?>,
        showDropdown: false,
        addTag(tag) {
            if (!<?php echo $args['x-model']; ?>.includes(tag)) { <?php echo $args['x-model']; ?>.push(tag); }
            this.showDropdown = false;
        },
        removeTag(tag) {
            <?php echo $args['x-model']; ?> = <?php echo $args['x-model']; ?>.filter(item => item !== tag);
        },
    }">

        <!-- Selected tags -->
        <template x-for="tag in <?php echo $args['x-model']; ?>" :key="tag">
            <div class="w-fit">
                <div class="flex items-center border border-black/20 pl-3 pr-1 h-8 rounded-full">
                    <span class="text-14 w-fit" x-text="tag"></span>
                    <button type="button" class="opacity-50 hover:opacity-100" x-on:click="removeTag(tag)">
                        <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg'; ?>" />
                    </button>
                </div>
            </div>
        </template>

         <!-- Add tag button -->
        <div class="relative">
            <button type="button" class="w-fit" x-on:click="showDropdown = true">
                <div class="flex items-center border border-black/20 pl-3 pr-2 h-8 rounded-full">
                    <span class="text-14 w-fit">Add an option</span>
                    <img class="w-4 h-4 ml-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron-down.svg'; ?>" />
                </div>
            </button>
            <ul class="absolute z-10 top-full bg-white border border-black/40 rounded-md shadow-sm max-h-60 overflow-y-auto w-full"
                x-show="showDropdown" x-cloak
                x-on:click.away="showDropdown = false"
            >
                <template x-for="tag in tagOptions" :key="tag">
                    <li
                        x-on:click="addTag(tag)"
                        class="px-4 py-2 hover:bg-yellow-10 cursor-pointer"
                        tabindex="0"
                        x-on:keydown.enter.prevent="addTag(tag)"
                    >
                        <span x-text="tag"></span>
                    </li>
                </template>
            </ul>
        </div>
    </div>
</fieldgroup>
