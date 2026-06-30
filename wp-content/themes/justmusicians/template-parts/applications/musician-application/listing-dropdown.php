<?php
$user_listings = $args['listings'] ?? [];
$listings_options = [];
foreach ($user_listings as $listing_id => $name) {
    $listings_options[] = [
        'value'         => (string) $listing_id,
        'label'         => $name,
        'thumbnail_url' => get_the_post_thumbnail_url($listing_id, 'small') ?: '',
    ];
}
$listings_options[] = [
    'value'         => '',
    'label'         => 'Create New Musician Listing',
    'thumbnail_url' => '',
];
?>

<div
    x-data="{
        options: <?php echo clean_arr_for_doublequotes($listings_options); ?>,
        selected: {},
        showDropdown: false,
        select(option) {
            this.selected = option;
            this.showDropdown = false;
            createNewListing = option.label == 'Create New Musician Listing';
        }
    }"
    x-on:click.away="showDropdown = false"
>
    <h2 class="text-20 sm:text-25 font-bold mb-4">Musician</h2>

    <!-- Dropdown button -->
    <button type="button" class="flex items-center gap-2 w-full px-3 py-2 border border-black/20 rounded-sm text-16"
        x-on:click="showDropdown = !showDropdown">
        <img class="w-12 h-12 object-cover shrink-0"
            x-show="selected && selected.thumbnail_url"
            x-bind:src="selected.thumbnail_url"
        />
        <span class="grow text-left" x-text="selected.label || 'Select one of your existing listings'"></span>
        <img class="w-3 h-3 shrink-0" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron-down.svg'; ?>" />
    </button>

    <!-- Dropdown options -->
    <ul class="absolute z-10 w-[calc(100%-2rem)] bg-white border border-black/40 rounded-md shadow-sm max-h-56 overflow-y-auto mt-1" x-show="showDropdown" x-cloak>
        <template x-for="(opt, index) in options" :key="index">
            <li x-on:click="select(opt)"
                class="flex items-center gap-2 px-4 py-2 hover:bg-yellow-10 cursor-pointer text-16"
                :class="selected && selected.value === opt.value ? 'bg-yellow-10 font-semibold' : ''">
                <img x-show="opt.thumbnail_url" :src="opt.thumbnail_url" class="w-12 h-12 object-cover shrink-0" />
                <span x-text="opt.label"></span>
            </li>
        </template>
    </ul>

    <input type="hidden" name="listing_id" :value="selected ? selected.value : ''" />
</div>
