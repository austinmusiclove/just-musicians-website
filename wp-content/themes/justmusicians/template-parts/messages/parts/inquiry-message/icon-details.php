<div class="grid gap-x-12 gap-y-4 w-full">

    <!-- Date -->
    <div class="flex items-center gap-1">
        <img style="height: 1.5rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/calendar.svg'; ?>" />
        <span class="text-16 whitespace-nowrap overflow-hidden text-ellipsis block"
            x-text="_showDate(message.inquiry)"
        ></span>
    </div>

    <!-- Time -->
    <div class="flex items-center gap-1">
        <img style="height: 1.5rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/clock.svg'; ?>" />
        <span class="text-16 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="_showTime(message.inquiry)"></span>
    </div>

    <!-- Zip code -->
    <div class="flex items-center gap-1">
        <img style="height: 1.5rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
        <span class="text-16 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="message.inquiry.zip_code"></span>
    </div>

    <!-- Budget -->
    <div class="flex items-center gap-1">
        <img style="height: 1.5rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/dollar.svg'; ?>" />
        <span class="text-16 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="_showBudget(message.inquiry)"></span>
    </div>

</div>
