<div class="popup-wrapper w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center p-4" x-show="showSignupModal" x-cloak>
    <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"
        x-on:click="showSignupModal = false; signupModalMessage = 'Sign up for an account'; loginModalMessage = 'Sign in to your account';"
    ></div>

    <div class="bg-white relative w-full h-auto md:w-auto flex items-center justify-center border-2 shadow-black-offset border-black" style="max-width: 780px;">

        <img class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" alt="Close signup modal"
            x-on:click="showSignupModal = false; signupModalMessage = 'Sign up for an account'; loginModalMessage = 'Sign in to your account';" />

        <div class="slide w-[32rem] overflow-y-auto max-h-[90vh]">

            <div class="flex flex-col justify-center lg:px-8 min-h-full py-12 sm:px-6">
                <?php echo get_template_part('template-parts/login/forms/signup', '', [ 'signup_redirect' => $args['signup_redirect'] ?? null ]); ?>
            </div>

        </div>

    </div>
</div>

