<div class="flex flex-col gap-2 min-w-0 border border-black/20 rounded bg-yellow-10/50" x-show="requestQuote || requestDraw" x-cloak>
    <label class="block bg-yellow-20 p-2 w-full p-2 flex items-center gap-1 rounded-t-sm font-bold">More Details</label>

    <fieldgroup class="grid sm:grid-cols-2 gap-2 p-4">

        <div x-show="requestQuote" x-cloak>
            <label class="mb-1 inline-block" for="minimum_guarantee">
                Min Guarantee
                <?php get_template_part('template-parts/global/tooltips/tooltip', '', [ 'tooltip' => 'What is your minimum guarantee payment requirement?' ]); ?>
            </label><br>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex pr-1 items-center text-grey pointer-events-none">$</span>
                <input class="!pl-8 w-full px-3 py-2" type="number" id="minimum_guarantee" name="minimum_guarantee" min="0" title="What is your minimum guarantee payment requirement?" x-model="minimumGuarantee">
                <input type="hidden" id="minimum_guarantee" name="minimum_guarantee" x-model="minimumGuarantee">
            </div>
        </div>

        <div x-show="requestDraw" x-cloak>
            <label class="mb-1 inline-block" for="draw_estimate">
                Draw Estimate
                <?php get_template_part('template-parts/global/tooltips/tooltip', '', [ 'tooltip' => 'How many people do you estimate you will be able to draw to the show?' ]); ?>
            </label><br>
            <div class="relative">
                <span class="absolute inset-y-0 right-0 pr-3 flex pl-1 items-center text-grey pointer-events-none">people</span>
                <input class="!pr-20 w-full px-3 py-2" type="number" id="draw_estimate" name="draw_estimate" min="0" title="How many people do you estimate you will be able to draw to the show?" x-model="drawEstimate">
                <input type="hidden" id="draw_estimate" name="draw_estimate" x-model="drawEstimate">
            </div>
        </div>

    </fieldgroup>

</div>
