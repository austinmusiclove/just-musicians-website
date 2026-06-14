<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] flex flex-col overflow-y-auto pr-2" x-show="showBudgetSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">How do you plan to pay musicians?</h2>

    <fieldset class="radio-buttons custom-radio" x-on:change="inquiryBudget = ''; inquiryCompensation = '';">
        <div>
            <input type="radio" id="budget_option_quotes" x-model="inquiryBudgetType" value="Request Quotes" checked>
            <span></span>
            <label class="text-16" for="budget_option_quotes">I'd like to get quotes from musicians</label>
        </div>
        <div>
            <input type="radio" id="budget_option_budget" x-model="inquiryBudgetType" value="Budget">
            <span></span>
            <label class="text-16" for="budget_option_budget">I have a budget</label>
        </div>
        <div>
            <input type="radio" id="budget_option_other" x-model="inquiryBudgetType" value="Other">
            <span></span>
            <label class="text-16" for="budget_option_other">I have other compensation in mind</label>
        </div>
    </fieldset>
    <input type="hidden" name="event_request_quote" x-bind:value="inquiryBudgetType == 'Request Quotes'" />

    <span x-show="inquiryBudgetType == 'Budget'" x-cloak>
        <p>Live Music Budget</p>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-grey pointer-events-none">$</span>
            <input class="!pl-7 w-full px-3 py-2" type="number" name="event_budget" x-model="inquiryBudget">
        </div>
    </span>
    <span x-show="inquiryBudgetType == 'Other'" x-cloak>
        <p>Please add details about how musicians will be compensated.</p>
        <div class="relative">
            <textarea class="w-full h-32 mb-6 flex-shrink-0" name="event_compensation" x-model="inquiryCompensation"></textarea>
        </div>
    </span>

    <div class="mt-auto pt-6 flex justify-end gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('location')">Back</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2"
            x-on:click="_showInquirySlide('genre')"
        >Next</button>
    </div>

</div>
