<div x-data="{
        options: (() => {
            let opts = <?php echo clean_arr_for_doublequotes($args['options']); ?>;
            <?php if (!empty($args['other_option'])) : ?>
                opts = [...opts, 'Other'];
            <?php endif; ?>
            return opts;
        })(),
        selected: '',
        showDropdown: false,
        otherValue: '',
        otherSelected: false,
        select(option) {
            if (option === 'Other') {
                this.otherSelected = true;
                this.otherValue = '';
                this.selected = '';
            } else {
                this.otherSelected = false;
                this.otherValue = '';
                this.selected = option;
            }
            this.showDropdown = false;
        },
        deselect() {
            this.selected = '';
            this.otherValue = '';
            this.otherSelected = false;
        }
    }"
    x-on:clear-form.window="deselect();"
>
    <div class="flex flex-row">
        <label id="<?php echo $args['input_name']; ?>" for="<?php echo $args['input_name']; ?>" class="mb-1 mr-2 inline-block">
            <?php echo $args['title']; ?>
            <?php if ($args['required']) { ?><span class="text-red">*</span><?php } ?>
        </label><br>
        <?php if (!empty($args['tooltip'])) { echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => $args['tooltip'], ]); } ?>
    </div>
    <div class="relative flex flex-col items-center justify-between">
        <button type="button" class="w-full inline-flex justify-between items-center grow px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-black/40 rounded-md focus:outline-none"
            x-on:click="showDropdown = !showDropdown"
        >
            <span x-text="otherSelected ? 'Other' : (selected || 'Select one')"></span>
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <ul class="absolute z-10 top-full left-0 w-full bg-white border border-black/40 rounded-md shadow-sm max-h-60 overflow-y-auto" style="margin-top: -1px"
            x-show="showDropdown" x-cloak
            x-on:click.away="showDropdown = false"
        >
            <template x-for="(option, index) in options" :key="index">
                <li
                    @click="select(option)"
                    class="px-4 py-2 hover:bg-yellow-10 cursor-pointer"
                    tabindex="0"
                    @keydown.enter.prevent="select(option)"
                >
                    <span x-text="option"></span>
                </li>
            </template>
        </ul>

        <input type="text" class="px-4 py-2 border border-black/40 rounded-md" placeholder="Please specify"
            x-show="otherSelected" x-cloak
            x-model="otherValue"
            x-on:input="selected = otherValue"
        />

        <!-- Hidden input to store selected option -->
        <input type="hidden" name="<?php echo $args['input_name']; ?>" x-model="selected" required>
    </div>
</div>
