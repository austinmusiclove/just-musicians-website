<div class="flex flex-col gap-2 mb-4">
    <div>
        <label class="text-14 font-bold">Live Music Budget</label>
        <div class="relative">
            <img class="h-5 absolute bottom-2.5 left-3 opacity-60" src="<?php echo get_template_directory_uri() . '/lib/images/icons/money-bill.svg'; ?>" />
            <span class="absolute inset-y-0 left-0 pl-9 flex items-center pointer-events-none">$</span>
            <input class="!pl-12 w-full px-3 py-2" type="number" min="0" name="event_budget" placeholder="e.g. 2,000" x-bind:value="budget">
        </div>
    </div>
    <div>
        <label class="text-14 font-bold">Compensation Details</label>
        <textarea name="event_compensation" x-bind:value="compensation" class="w-full h-24 sm:h-40" placeholder="Describe compensation for musicians"></textarea>
    </div>
</div>

<div class="flex flex-col gap-2 mb-4">
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="hidden" name="event_request_quote" value="0" />
        <input type="checkbox" name="event_request_quote" value="1" x-bind:checked="requestQuote" class="w-4 h-4" />
        <span class="text-16">Request quote from musicians</span>
        <?php echo get_template_part('template-parts/global/tooltips/tooltip', '', [ 'tooltip' => 'Select this if you want musicians to give you a price for their service' ]); ?>
    </label>
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="hidden" name="event_request_draw" value="0" />
        <input type="checkbox" name="event_request_draw" value="1" x-bind:checked="requestDraw" class="w-4 h-4" />
        <span class="text-16">Request draw estimate from musicians</span>
        <?php echo get_template_part('template-parts/global/tooltips/tooltip', '', [ 'tooltip' => 'Select this if you want musicians to let you know how many guests they believe they will attract to the show' ]); ?>
    </label>
</div>
