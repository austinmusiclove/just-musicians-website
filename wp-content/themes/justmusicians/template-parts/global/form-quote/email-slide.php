<div data-slide="2" class="slide w-[32rem] pb-8 grow" x-show="showEmailSlide" x-cloak>

    <div class="progress-tracker bg-yellow h-2 w-64 absolute top-0 left-0"></div>

    <h2 class="font-bold font-poppins text-20 mb-8">Where can we send responses from musicians in your area?</h2>
    <p class="text-16 mb-8">Your email will not be directly shared with musicians.</p>

    <input type="email" name="inquiry_email" placeholder="Enter your email" />

    <div class="absolute bottom-10 right-10 flex flex-row gap-1">
        <button type="button" data-trigger="slide-1" class="bg-white shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-black font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('location')">Back</button>
        <button type="button" data-trigger="slide-3" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-4 py-2" x-on:click="_showInquirySlide('thankyou');">Submit</button>
    </div>

</div>
