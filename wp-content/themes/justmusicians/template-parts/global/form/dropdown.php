<div x-data="{
        options: <?php echo clean_arr_for_doublequotes($args['options']); ?>,
        selected: '<?php echo $args['selected']; ?>',
        showDropdown: false,
        select(option) {
            this.selected = option;
            this.showDropdown = false;
            $nextTick(() => $dispatch('<?php echo $args['input_name']; ?>-changed', { value: option }));
        }
    }"
    x-on:click.away="showDropdown = false"

    class="relative"
>
    <!-- Button -->
    <button type="button" class="flex items-center gap-1 px-3 py-1.5 border border-black/20 rounded-sm text-14 hover:border-black" x-on:click="showDropdown = !showDropdown">

        <span x-text="options.find(option => option.value === selected).label"></span>
        <img class="w-3 h-3" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron-down.svg'; ?>" />

    </button>

    <!-- Options -->
    <ul class="absolute z-10 top-full left-0 w-56 bg-white border border-black/40 rounded-md shadow-sm max-h-56 overflow-y-auto mt-1" x-show="showDropdown" x-cloak>

        <template x-for="(opt, i) in options" :key="i">
            <li x-on:click="select(opt.value)"
                class="px-4 py-2 hover:bg-yellow-10 cursor-pointer text-14"
                :class="selected === opt.value ? 'bg-yellow-10 font-semibold' : ''"
                x-text="opt.label"></li>
        </template>

    </ul>

    <!-- Input -->
    <input type="hidden" name="<?php echo $args['input_name']; ?>" x-model="selected" />

</div>
