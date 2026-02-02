<section class="flex flex-col gap-5">

    <div class="flex items-center gap-2">
        <img class="h-6 opacity-80" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
        <h2 class="text-25 font-bold">Venue <span class="text-red">*</span></h2>
        <span id="venue-active-search-spinner" class="inset-0 flex items-center justify-center htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
        </span>
    </div>

    <div class="relative">


        <div x-data="{
                tags: [],
                showOptions: false,
                _selectVenue(input, value) {
                    this.tags = [];
                    $refs.venueIdInput.value = value.ID;
                    $refs.venueNameInput.value = value.name;
                    addTag(this, input, value, 'error-toast');
                },
                _removeTag(index) {
                    $refs.venueIdInput.value = '';
                    removeTag(this, index);
                },
            }"
            x-on:clear-form.window="_removeTag(0);"
        >

            <input id="venue_id_input" type="hidden" name="venue_id" x-ref="venueIdInput">
            <input id="venue_name_input" type="hidden" name="venue_name" x-ref="venueNameInput">
            <div class="relative">
                <div class="relative">
                    <input type="text" name="s" class="w-full" autocomplete="off" placeholder="Search for venues"
                        x-ref="venuesInput"
                        x-on:focus="showOptions = true; $dispatch('updatevenueoptions');"
                        x-on:click.away="showOptions = false"
                        hx-get="<?php echo site_url('/wp-html/v1/venue-search-options'); ?>"
                        hx-trigger="input changed delay:300ms, updatevenueoptions"
                        hx-target="#venue-active-search-results"
                        hx-indicator="#venue-active-search-spinner"
                    />
                </div>
                <div id="venue-active-search-results" class="z-10" x-show="showOptions" x-cloak>
                    <?php echo get_template_part('template-parts/search/venues-search-state-1', '', []); ?>
                </div>
            </div>


            <div class="gap-1 mt-4 flex flex-wrap gap-2">
                <!-- Alpine template w/classes of tags above -->
                <template class="w-fit" x-for="(tag, index) in tags" :key="index">
                    <div class="flex flex-col items-start bg-yellow-light pl-2 py-1 pr-8 relative rounded-md text-14 w-fit">
                        <span class="font-bold" x-text="tag['name']"></span>
                        <span x-text="tag['street_address']"></span>
                        <span x-text="tag['address_locality'] + ', ' + tag['address_region'] + ' ' + tag['postal_code']"></span>
                        <button type="button" class="absolute top-0 right-0 opacity-50 hover:opacity-100" x-on:click="_removeTag(index)">
                            <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg'; ?>" />
                        </button>
                    </div>
                </template>

            </div>

        </div>
    </div>
</section>
