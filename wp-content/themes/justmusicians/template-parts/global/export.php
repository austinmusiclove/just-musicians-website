<div x-data="{ showDropdown: false }"
    x-on:click.away="showDropdown = false"

    class="relative"
>
    <!-- Button -->
    <button id="<?php echo esc_attr($args['button_id']); ?>" type="button" class="relative flex items-center gap-1 px-3 py-1.5 border border-black/20 rounded-sm text-14 hover:border-black"
        x-on:click="showDropdown = !showDropdown"
    >

        <span class="htmx-indicator-replace">Export</span>
        <img class="w-3 h-3 htmx-indicator-replace" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron-down.svg'; ?>" />
        <span class="absolute inset-0 flex items-center justify-center bg-white rounded-sm htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '6', 'color' => 'yellow']); ?>
        </span>

    </button>

    <!-- Options -->
    <ul class="absolute z-10 top-full left-0 w-56 bg-white border border-black/40 rounded-md shadow-sm max-h-56 overflow-y-auto mt-1" x-show="showDropdown" x-cloak>

        <?php foreach ($args['options'] as $option) { ?>
            <li class="flex items-center justify-between gap-8 px-4 py-2 hover:bg-yellow-10 cursor-pointer text-14"
                hx-get="<?php echo esc_url($option['endpoint']); ?>"
                hx-trigger="click"
                hx-target="#export-toasts"
                hx-swap="innerHTML"
                hx-indicator="#<?php echo esc_attr($args['button_id']); ?>"
                x-on:click="showDropdown = false"
            >
                <span><?php echo esc_html($option['label']); ?></span>
                <img class="w-4 h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/download.svg'; ?>" />
            </li>
        <?php } ?>

    </ul>

    <div id="export-toasts" class="hidden"></div>

</div>
