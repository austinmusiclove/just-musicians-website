<div class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem]" x-data="{ inquiryEquipment: '', inquiryEquipmentDetails: '' }" x-show="showEquipmentSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">Do you need musicians to provide their own sound equipment?</h2>

    <fieldset class="radio-buttons custom-radio">
        <div>
            <input type="radio" id="equipment-all" name="inquiry_equipment_requirement" x-model="inquiryEquipment" value="Musicians must be able to bring and set up all sound equipment">
            <span></span>
            <label class="text-16" for="equipment-all">Musicians must be able to bring and set up all sound equipment.</label>
        </div>
        <div>
            <input type="radio" id="equipment-some" name="inquiry_equipment_requirement" x-model="inquiryEquipment" value="Some or all sound equipment will be provided to musicians">
            <span></span>
            <label class="text-16" for="equipment-some">Some or all sound equipment will be provided to musicians.</label>
        </div>
    </fieldset>

    <span x-show="inquiryEquipment" x-cloak>
        <p class="text-16 mt-8">Include any details you'd like about who is responsible for providing and setting up sound equipment and/or what equipment is required.</p>
        <textarea class="w-full h-40 mb-6" name="inquiry_equipment_details" x-model="inquiryEquipmentDetails"></textarea>
    </span>

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('performers')">Back</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2 disabled:bg-grey disabled:text-white"
            x-text="inquiryEquipment || inquiryEquipmentDetails ? 'Next' : 'Skip'"
            x-on:click="_showInquirySlide('details')"
        >Next</button>
    </div>

</div>
