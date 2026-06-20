<div class="flex items-center gap-1" x-data="{ hasLocation() { return address_line_1 || address_line_2 || city || state || zip_code; } }">

    <img style="height: 1rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />

    <span class="text-16"
        x-show="hasLocation()" x-cloak
        x-text="`${address_line_1} ${address_line_2} ${[city, state].filter(Boolean).join(', ')} ${zip_code}`">
    </span>

    <span class="text-16 text-black/50" x-show="!hasLocation()" x-cloak>Location not specified</span>

</div>
