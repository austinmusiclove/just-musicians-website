<section class="overflow-hidden bg-white py-16 md:py-24">
    <div class="container relative isolate">
        <div class="mx-auto grid max-w-6xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:grid-cols-5 lg:items-center xl:gap-x-12">

            <div class="w-full flex-auto col-span-3">
                <p class="font-sun-motter text-13 uppercase tracking-wide text-brown-dark-3 mb-3">How It Works</p>
                <h2 class="font-sun-motter text-navy text-28 md:text-40 mb-4"><?php echo $args['title']; ?></h2>
                <p class="text-16 md:text-18 text-brown-dark-3 mb-8 md:mb-10"><?php echo $args['description']; ?></p>

                <ul role="list" class="grid grid-cols-1 gap-x-8 gap-y-3 text-base/7 text-brown-dark-3">
                    <?php foreach ($args['steps'] as $index => $step) { ?>
                        <li class="flex gap-x-3">
                            <span class="pointer-events-none font-sun-motter font-bold">
                                <?php echo esc_html($index + 1) . '.'; ?>
                            </span>
                            <?php echo esc_html($step); ?>
                        </li>
                    <?php } ?>
                </ul>

                <div class="my-8">
                    <button type="button" class="bg-navy text-white hover:bg-yellow hover:text-black shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3"
                        x-show="!loggedIn" x-cloak
                        x-on:click="showSignupModal = true; signupModalMessage = 'Sign up to send inquiries to musicians'"
                    >Tell Us About Your Event</button>
                    <button type="button" class="bg-navy text-white hover:bg-yellow hover:text-black shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3"
                        x-show="loggedIn" x-cloak
                        x-on:click="_openInquiryModal('', '')"
                    >Tell Us About Your Event</button>
                </div>

            </div>

            <div class="col-span-2 lg:order-first">
                <img class="hidden lg:block w-full max-w-none rounded-sm border-2 border-black shadow-black-offset h-96 object-cover lg:h-auto lg:max-w-lg"
                    src="<?php echo $args['image']; ?>"
                />
            </div>
        </div>
    </div>
</section>
