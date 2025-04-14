<div class="border border-black/20 p-8 <?php echo $args['responsive']; ?>">

    <h3 class="font-sun-motter text-20 mb-3">Request a quote from a local musician</h3>
    <p class="text-16 text-brown-dark-1 leading-tight mb-6">Tell us about your business or occasion to receive quotes from up to three local musicians.</p>

    <form method="post">

        <fieldset class="flex flex-col gap-y-2 mb-6">

            <div class="pseudo-select relative"
                x-data="{
                    showOptions: false,
                    selectedOption: $refs.defaultOption.getHTML()
                }"
            >
                <div data-trigger="what-do-you-need" class="w-full flex flex-row items-center justify-between cursor-pointer" x-on:click="showOptions = ! showOptions">
                    <span class="flex items-center gap-2" data-value="selected" x-ref="defaultOption" x-html="selectedOption">What do you need?</span>
                    <img src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
                </div>
                <!-- Options -->
                <div data-element="what-do-you-need" class="absolute top-full w-full left-0 px-4 py-4 bg-white font-regular border border-black/20 font-sans text-16 group-hover:flex flex-col shadow-md rounded-sm z-10"
                    x-show="showOptions" x-cloak
                    x-on:click.outside="showOptions = false"
                >
                    <span class="px-2 py-1.5 flex items-center gap-2 rounded-sm">
                        Select Musician Type
                    </span>
                    <span data-element="option" class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow/20 cursor-pointer" x-on:click="selectedOption = $el.getHTML()">
                        <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
                        Bands
                    </span>
                    <span data-element="option" class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow/20 cursor-pointer" x-on:click="selectedOption = $el.getHTML()">
                        <img class="h-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-person.svg'; ?>" />
                        Solo/Duo
                    </span>
                    <span data-element="option" class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow/20 cursor-pointer" x-on:click="selectedOption = $el.getHTML()">
                        <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-djs.svg'; ?>" />
                        DJs
                    </span>
                    <span data-element="option" class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow/20 cursor-pointer" x-on:click="selectedOption = $el.getHTML()">
                        <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-wedding.svg'; ?>" />
                        Wedding Music
                    </span>
                </div>
            </div>

            <!--<label for="zipcode" class="hidden">Enter zipcode</label>-->
            <input type="number" name="zipcode" placeholder="Enter zip code" />

        </fieldset>

    </form>
    <!-- Moved outside of form element to prevent form submission -->
    <!-- <button type="button" data-trigger="quote" class="<?php echo $args['button_color']; ?> shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3" x-on:click="showInquiryModal = true; showSlide1 = true;">Get Started</button> -->
    <button type="button" data-trigger="quote" class="<?php echo $args['button_color']; ?> shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3" x-on:click="showInquiryModalPlaceholder = true;">Get Started</button>
</div>
