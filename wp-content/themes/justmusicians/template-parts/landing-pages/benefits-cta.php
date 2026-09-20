<section class="overflow-hidden bg-white py-16 md:py-24">
    <div class="container relative isolate">
        <div class="mx-auto grid max-w-6xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:grid-cols-5 lg:items-center xl:gap-x-12">

            <div class="w-full flex-auto col-span-3">
                <p class="font-sun-motter text-13 uppercase tracking-wide text-brown-dark-3 mb-3">Musicians</p>
                <h2 class="font-sun-motter text-navy text-28 md:text-40 mb-4"><?php echo $args['title']; ?></h2>
                <p class="text-16 md:text-18 text-brown-dark-3 mb-8 md:mb-10"><?php echo $args['description']; ?></p>

                <ul role="list" class="grid grid-cols-1 gap-x-8 gap-y-3 text-base/7 text-brown-dark-3">
                    <?php foreach ($args['benefits'] as $benefit) { ?>
                        <li class="flex items-start gap-x-3">
                            <img class="h-6 w-6 shrink-0 mt-0.5"
                                src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/yellow-circle-check.svg"
                            />
                            <?php echo $benefit; ?>
                        </li>
                    <?php } ?>
                </ul>

                <div class="my-8">
                    <a class="inline-block bg-yellow hover:bg-navy hover:text-white text-black shadow-black-offset border-2 border-black font-sun-motter text-16 px-6 md:px-8 py-3"
                        <?php if (!is_user_logged_in() and isset($args['sign_up_to_access'])) { ?>
                            x-on:click="showLoginModal = false; showSignupModal = true; signupModalMessage = '<?php echo $args['sign_up_to_access']; ?>';"
                        <?php } else { ?>
                            href="<?php echo $args['cta_url']; ?>"
                        <?php } ?>
                    >
                        <?php echo $args['cta_text']; ?>
                    </a>
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
