<section class="flex flex-col gap-5">

    <div class="flex items-center gap-2">
        <img class="h-6 opacity-80" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
        <h2 class="text-25 font-bold">Venues Played</h2>
    </div>

    <div class="relative z-0">
        <!-- Venues Played -->

        <input type="hidden" name="venues_played_unverified[]">
        <template x-for="(venue, index) in allVenuesPlayed" :key="index">
            <input type="hidden" name="venues_played_unverified[]" x-bind:value="venue.ID" />
        </template>
        <input type="hidden" name="venues_played_verified[]">
        <template x-for="(venue_id, index) in verifiedVenueIds" :key="index">
            <template x-if="allVenuesPlayed.some(venue => venue.ID === venue_id)">
                <input type="hidden" name="venues_played_verified[]" :value="venue_id" />
            </template>
        </template>

        <!-- Depends on tag-input-scripts.js -->
        <div x-data="{
            tags: allVenuesPlayed,
            _addTag(input, value)    { addTag(this, input, value, 'error-toast'); },
            _removeTag(index) { removeTag(this, index); },
            showOptions: false,
        }">
            <div class="relative">
                <div class="relative">
                    <input type="text" name="s" class="w-full" autocomplete="off" placeholder="Search for venues"
                        x-ref="venuesInput"
                        x-on:focus="showOptions = true; $dispatch('updatevenueoptions');"
                        x-on:click.away="showOptions = false"
                        hx-get="<?php echo site_url('/wp-html/v1/venue-search-options'); ?>"
                        hx-trigger="input changed delay:300ms, updatevenueoptions"
                        hx-target="#venue-active-search-results"
                    />
                </div>
                <div id="venue-active-search-results" class="z-10" x-show="showOptions" x-cloak>
                    <?php echo get_template_part('template-parts/search/venues-search-state-1', '', array()); ?>
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
