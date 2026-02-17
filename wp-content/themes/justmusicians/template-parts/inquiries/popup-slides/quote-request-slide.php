<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem] max-h-[60vh] flex flex-col overflow-y-auto pr-2" x-show="showQuoteReqSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">How many quotes would you like to receive?</h2>

    <fieldset class="radio-buttons custom-radio">
        <div>
            <input type="radio" id="between-1-5-quotes" name="inquiry_quote_request" value="1-5">
            <span></span>
            <label for="between-1-5-quotes">1-5</label>
        </div>
        <div>
            <input type="radio" id="between-5-10-quotes" name="inquiry_quote_request" value="5-10">
            <span></span>
            <label for="between-5-10-quotes">5-10</label>
        </div>
        <div>
            <input type="radio" id="between-10-20-quotes" name="inquiry_quote_request" value="10-20">
            <span></span>
            <label for="between-10-20-quotes">10-20</label>
        </div>
        <div>
            <input type="radio" id="over-20-quotes" name="inquiry_quote_request" value="20+">
            <span></span>
            <label for="over-20-quotes">20+</label>
        </div>
        <div>
            <input type="radio" id="manual-quotes" name="inquiry_quote_request" value="I'd like to manually select the musicians who can submit a quote">
            <span></span>
            <label for="manual-quotes">I'd like to manually select the musicians who can submit a quote</label><br>
        </div>
    </fieldset>

    <div class="mt-auto pt-6 flex justify-end gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('details')">Back</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('thankyou');" >Submit</button>
    </div>

</div>
