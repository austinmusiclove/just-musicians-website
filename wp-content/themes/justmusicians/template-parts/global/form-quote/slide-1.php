<div data-slide="1" class="slide w-[32rem] pb-8 grow" x-show="showSlide1" x-cloak>

    <div class="progress-tracker bg-yellow h-2 w-24 absolute top-0 left-0"></div>


        <h2 class="font-bold font-poppins text-20 mb-8">What type of lorem ipsum do you need?</h2>

        <form>
            <fieldset class="radio-buttons custom-radio">
                <div>
                    <input type="radio" id="button-1" name="radio-group" value="Lorem ipsum dolor sit amet">
                    <span></span>
                    <label for="button-1">Lorem ipsum dolor sit amet</label>
                </div>
                <div>
                    <input type="radio" id="button-2" name="radio-group" value="Proin gravida aedipsicing el elit">
                    <span></span>
                    <label for="button-2">Proin gravida aedipsicing el elit</label>
                </div>
                <div>
                    <input type="radio" id="button-3" name="radio-group" value="Ut enim ad minim veniam">
                    <span></span>
                    <label for="button-3">Ut enim ad minim veniam</label>
                </div>
                <div>
                    <input type="radio" id="button-4" name="radio-group" value="Quis nostrud exercitation ullamco">
                    <span></span>
                    <label for="button-4">Quis nostrud exercitation ullamco</label>
                </div>
                <div>
                    <input type="radio" id="button-5" name="radio-group" value="Aboris nisi ut aliquip ex ea commodo consequat">
                    <span></span>
                    <label for="button-5">Aboris nisi ut aliquip ex ea commodo consequat</label><br>
                </div>
            </fieldset>
        </form>

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" data-trigger="slide-2" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2" x-on:click="showSlide2 = true; showSlide1 = false;">Next</button>
    </div>

</div>


