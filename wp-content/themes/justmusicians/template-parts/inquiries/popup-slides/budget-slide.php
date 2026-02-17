<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] flex flex-col overflow-y-auto pr-2" x-show="showBudgetSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">How do you plan to pay musicians?</h2>

    <fieldset class="radio-buttons custom-radio">
        <div>
            <input type="radio" id="request_quotes" name="inquiry_budget_type" x-model="inquiryBudgetType" value="Request Quotes" checked>
            <span></span>
            <label class="text-16" for="request_quotes">I'd like to get quotes from musicians</label>
        </div>
        <div>
            <input type="radio" id="budget_guarantee" name="inquiry_budget_type" x-model="inquiryBudgetType" value="Guarantee">
            <span></span>
            <label class="text-16" for="budget_guarantee">I have a budget</label>
        </div>
        <div>
            <input type="radio" id="budget_door_deal" name="inquiry_budget_type" x-model="inquiryBudgetType" value="Door Deal">
            <span></span>
            <label class="text-16" for="budget_door_deal">Door Deal</label>
        </div>
        <div>
            <input type="radio" id="budget_bar_deal" name="inquiry_budget_type" x-model="inquiryBudgetType" value="Bar Deal">
            <span></span>
            <label class="text-16" for="budget_bar_deal">Bar Deal</label>
        </div>
        <div>
            <input type="radio" id="budget_other" name="inquiry_budget_type" x-model="inquiryBudgetType" value="Other">
            <span></span>
            <label class="text-16" for="budget_other">Other</label>
        </div>
    </fieldset>

    <span x-show="inquiryBudgetType == 'Guarantee'" x-cloak>
        <p>Live Music Budget</p>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-grey pointer-events-none">$</span>
            <input class="!pl-7 w-full px-3 py-2" type="number" name="inquiry_budget">
        </div>
    </span>
    <span x-show="inquiryBudgetType == 'Door Deal'" x-cloak>
        <p>% of Door/Ticket Sales</p>
        <div class="relative">
            <input class="!pl-7 w-full px-3 py-2" type="number" name="inquiry_percent_of_door" x-on:input="$el.value = Math.min(100, Math.max(0, $el.value))">
            <span class="absolute inset-y-0 right-0 pr-8 flex items-center text-grey pointer-events-none">%</span>
        </div>
    </span>
    <span x-show="inquiryBudgetType == 'Bar Deal'" x-cloak>
        <p>% of Bar Sales</p>
        <div class="relative">
            <input class="!pl-7 w-full px-3 py-2" type="number" name="inquiry_percent_of_bar" min="0" max="100" x-on:input="$el.value = Math.min(100, Math.max(0, $el.value))">
            <span class="absolute inset-y-0 right-0 pr-8 flex items-center text-grey pointer-events-none">%</span>
        </div>
    </span>

    <div class="mt-auto pt-6 flex justify-end gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('location')">Back</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2"
            x-on:click="_showInquirySlide('genre')"
        >Next</button>
    </div>

</div>
