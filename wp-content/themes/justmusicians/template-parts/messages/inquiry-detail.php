
<template x-if="inquiry">

    <div class="flex-1 overflow-y-auto my-8 pr-8"
        x-data="{
            _showDate(inquiry)   { return showDate(inquiry); },
            _showTime(inquiry)   { return showTime(inquiry); },
            _showBudget(inquiry) { return showBudget(inquiry); },
        }"
    >

        <h2 class="mb-8 font-bold text-20" x-text="inquiry.subject"></h2>

        <!-- Date -->
        <div class="flex items-center gap-1 pb-4">
            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/calendar.svg'; ?>" />
            <span class="text-16 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="_showDate(inquiry)" ></span>
        </div>

        <!-- Time -->
        <div class="flex items-center gap-1 pb-4">
            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/clock.svg'; ?>" />
            <span class="text-16 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="_showTime(inquiry)" ></span>
        </div>

        <!-- Zip code -->
        <div class="flex items-center gap-1 pb-4">
            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
            <span class="text-16 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="inquiry.zip_code"></span>
        </div>

        <!-- Budget -->
        <div class="flex items-center gap-1 pb-4">
            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/dollar.svg'; ?>" />
            <span class="text-16 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="_showBudget(inquiry)"></span>
        </div>

        <!-- Divider -->
        <div class="border-t border-black/20 "></div>

        <!-- Genres -->
        <template x-if="inquiry.genres && inquiry.genres.length > 0">
            <div class="py-4">
                <h3 class="my-2 font-bold text-16">Genres</h3>
                <div class="flex flex-wrap items-center gap-1">
                    <template x-for="genre in inquiry.genres">
                        <span class="bg-yellow-light cursor-pointer hover:bg-yellow px-2 py-0.5 rounded-full font-bold text-12" x-text="genre"></span>
                    </template>
                </div>
            </div>
        </template>

        <!-- Details -->
        <template x-if="inquiry.details && inquiry.details != ''">
            <div class="py-4">
                <h3 class="my-2 font-bold text-16">Details</h3>
                <p class="text-16" x-html="inquiry.details"></p>
            </div>
        </template>

    </div>

</template>
