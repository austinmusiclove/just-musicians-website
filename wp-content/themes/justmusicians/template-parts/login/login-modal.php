<div data-popup="quote" class="popup-wrapper w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center p-4" x-show="showLoginModal" x-cloak>
    <div data-trigger="quote" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer" x-on:click="showLoginModal = false; loginModalMessage = 'Sign in to your account';"></div>

    <div class="bg-white relative w-full md:w-auto flex items-center justify-center border-2 shadow-black-offset border-black" style="max-width: 780px;">

        <img data-trigger="quote" class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"
            x-on:click="showLoginModal = false; loginModalMessage = 'Sign in to your account';" />

        <div class="slide w-[32rem] grow">

            <div  class="flex flex-col justify-center lg:px-8 min-h-full py-12 sm:px-6">

                <?php
                    echo get_template_part('template-parts/login/forms/login', '', []);
                ?>
            </div>

        </div>


    </div>
</div>
