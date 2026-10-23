<div class="flex flex-col gap-4 mb-4">
    <div class="flex flex-col gap-4">
        <label class="text-18 font-bold inline-block">Live Music Budget</label>
        <div class="relative">
            <img class="h-5 absolute bottom-2.5 left-3 opacity-60" src="<?php echo get_template_directory_uri() . '/lib/images/icons/money-bill.svg'; ?>" alt="" />
            <span class="absolute inset-y-0 left-0 pl-9 flex items-center pointer-events-none">$</span>
            <input class="!pl-12 w-full px-3 py-2" type="number" min="0" name="event_budget" placeholder="e.g. 2,000" x-bind:value="budget">
        </div>
    </div>
    <div class="flex flex-col gap-4">
        <label class="text-18 font-bold inline-block">Compensation Details</label>
        <textarea name="event_compensation" x-bind:value="compensation" class="w-full h-24 sm:h-40" placeholder="Describe compensation for musicians"></textarea>
    </div>
</div>
