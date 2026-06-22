<div class="flex items-center gap-1" x-data="{ hasLocation() { return addressLine1 || addressLine2 || city || state || zipCode; } }">

    <img style="height: 1rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />

    <span class="text-16"
        x-show="hasLocation()" x-cloak
        x-text="`${addressLine1} ${addressLine2} ${[city, state].filter(Boolean).join(', ')} ${zipCode}`">
    </span>

    <span class="text-16 text-black/50" x-show="!hasLocation()" x-cloak>Location not specified</span>

</div>
