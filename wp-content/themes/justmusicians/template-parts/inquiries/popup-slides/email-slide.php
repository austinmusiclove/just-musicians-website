<div data-slide="2" class="slide pb-8 grow w-[18rem] sm:w-[32rem] md:min-h-[30rem]" x-show="showEmailSlide" x-cloak>


    <h2 class="font-bold font-poppins text-20 mb-8">Where can we send the responses from musicians in your area?</h2>
    <p class="text-16 mb-8">Your email will not be directly shared with musicians.</p>

    <input type="email" name="inquiry_email" placeholder="Enter your email" />

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('location')">Back</button>
        <button type="button" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('thankyou');">Submit</button>
    </div>

</div>
