<div data-popup="quote" class="popup-wrapper w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center p-4" x-show="<?php echo $args['alpine_login_show_var']; ?>" x-cloak>
    <!-- TODO need to go to slide 5 if not done or just close it all if done and if got o slide 5 need to remember what slide they were on to return to it -->
    <div data-trigger="quote" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"
        x-on:click="<?php echo $args['alpine_login_show_var']; ?> = false"
    ></div>

    <div class="bg-white relative w-full md:w-auto flex items-center justify-center border-2 shadow-black-offset border-black" style="max-width: 780px;">

    <img data-trigger="quote" class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"
        x-on:click="<?php echo $args['alpine_login_show_var']; ?> = false;" />

    <button data-slide="request-password-reset" onclick="showLoginForm()" class="p-2 absolute top-2 left-2 hidden">
        <img class="opacity-60 hover:opacity-100 w-3 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron-left-sharp-solid.svg';?>"/>
    </button>

    <div class="slide w-[32rem] grow">
        
    <div  class="flex flex-col justify-center lg:px-8 min-h-full py-12 sm:px-6">

        <?php
            echo get_template_part('template-parts/login/forms/login', '', [
                'alpine_login_show_var' => 'showLoginModal',
                'alpine_signup_show_var' => 'showSignupModal',
            ]);

            echo get_template_part('template-parts/login/forms/request-password-reset', '', []);

        ?>
    </div>
        

    </div>


    </div>
</div>
