
<template x-if="inquiry">

    <div class="flex-1 overflow-y-auto my-8 pr-8">

        <h2 class="mb-8 font-bold text-20" x-text="inquiry.subject"></h2>

        <!-- Date -->
        <template x-if="(inquiry.date_type && inquiry.date_type != '') || (inquiry.date && inquiry.date != '')">
            <div class="flex items-center gap-1 pb-4">
                <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/calendar.svg'; ?>" />
                <span class="text-16 whitespace-nowrap overflow-hidden text-ellipsis block"
                    x-data="{
                        showDate(inquiry) {
                            if (inquiry.date != '') {
                                return inquiry.date;
                            } else {
                                return inquiry.date_type;
                            }
                        },
                    }"
                    x-text="showDate(inquiry)"
                ></span>
            </div>
        </template>

        <!-- Time -->
        <template x-if="inquiry.time && inquiry.time != ''">
            <div class="flex items-center gap-1 pb-4">
                <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/clock.svg'; ?>" />
                <span class="text-16 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="inquiry.time"></span>
            </div>
        </template>

        <!-- Zip code -->
        <template x-if="inquiry.zip_code && inquiry.zip_code != ''">
            <div class="flex items-center gap-1 pb-4">
                <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
                <span class="text-16 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="inquiry.zip_code"></span>
            </div>
        </template>

        <!-- Details -->
        <template x-if="inquiry.details && inquiry.details != ''">
            <div class="py-4 border-t border-black/20 ">
                <h3 class="my-2 font-bold text-16">Details</h3>
                <p class="text-16" x-html="inquiry.details"></p>
            </div>
        </template>

    </div>

</template>
