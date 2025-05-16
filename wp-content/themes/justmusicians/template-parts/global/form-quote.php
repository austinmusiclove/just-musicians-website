<div class="border border-black/20 p-8 <?php echo $args['responsive']; ?>">

    <h3 class="font-sun-motter text-20 mb-3">Request a quote from a local musician</h3>
    <p class="text-16 text-brown-dark-1 leading-tight mb-6">Tell us about your occasion to receive quotes from musicians near you.</p>

    <form method="post">

        <fieldset class="flex flex-col gap-y-2 mb-6">

            <div class="pseudo-select relative"
                x-data="{
                    showOptions: false,
                    setDefaultOption() {
                        if (inquiryDateType == 'single-date') { return 'I have a specific date'; }
                        else if (inquiryDateType == 'multi-date') { return 'I have multiple dates'; }
                        else { return 'When do you need music?'; }
                    }
                }"
            >
                <div class="w-full flex flex-row items-center justify-between cursor-pointer" x-on:click="showOptions = ! showOptions">
                    <span class="flex items-center gap-2" data-value="selected" x-html="setDefaultOption()">When do you need music?</span>
                    <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
                </div>
                <!-- Options -->
                <div class="absolute top-full w-full left-0 px-4 py-4 bg-white font-regular border border-black/20 font-sans text-16 group-hover:flex flex-col shadow-md rounded-sm z-10"
                    x-show="showOptions" x-cloak
                    x-on:click.outside="showOptions = false"
                >
                    <span class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow/20 cursor-pointer" x-on:click="showOptions = false; inquiryDateType = 'single-date'">
                        I have a specific date
                    </span>
                    <span class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow/20 cursor-pointer" x-on:click="showOptions = false; inquiryDateType = 'multi-date'">
                        I have multiple dates
                    </span>
                </div>
            </div>

            <input type="text" name="event_zipcode"
                autocomplete="postal-code"
                maxlength="5"
                placeholder="Your event zip code"
                x-model="inquiryZipCode"
            />

        </fieldset>


        <button type="button" class="<?php echo $args['button_color']; ?> shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3"
            x-show="!loggedIn" x-cloak
            x-on:click="showSignupModal = true; signupModalMessage = 'Sign up to request quotes from musicians'"
        >Get Started</button>
        <button type="button" class="<?php echo $args['button_color']; ?> shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3"
            x-show="loggedIn" x-cloak
            x-on:click="_openInquiryModal('', '')"
        >Get Started</button>

    </form>
</div>
