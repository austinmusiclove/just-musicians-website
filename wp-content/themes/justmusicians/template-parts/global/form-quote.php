<h3 class="font-sun-motter text-20 mb-3">Request a quote from a local musician</h3>
<p class="text-16 text-brown-dark-1 leading-tight mb-6">Tell us about your business or occasion to receive quotes from up to three local musicians.</p>

<form method="post">

    <fieldset class="flex flex-col gap-y-2 mb-6">

        <label for="what" class="hidden">What do you need?</label>
        <select id="what" name="what">
            <option value="" disabled selected hidden>What do you need?</option>
            <option value="option1a">Option 1A</option>
            <option value="option1b">Option 1B</option>
            <option value="option1c">Option 1C</option>
        </select>

        <label for="zipcode" class="hidden">Enter zipcode</label>
        <input type="number" id="zipcode" name="zipcode" placeholder="Enter zip code" />

    </fieldset>

</form>
<!-- Moved outside of form element to prevent form submission -->
<button data-trigger="quote" class="bg-navy shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-5 py-3">Get Started</button>

<?php echo get_template_part('template-parts/global/form-quote/popup', '', array()); ?> 
