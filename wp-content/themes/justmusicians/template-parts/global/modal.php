<div data-popup="quote" class="popup-wrapper pt-28 md:pt-0 w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center" x-show="<?php echo $args['alpine_show_var']; ?>" x-cloak>
    <!-- TODO need to go to slide 5 if not done or just close it all if done and if got o slide 5 need to remember what slide they were on to return to it -->
    <div data-trigger="quote" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"
        x-on:click="<?php echo $args['alpine_show_var']; ?> = false"
    ></div>

    <div class="bg-white relative p-8 md:p-20 relative w-full h-full md:w-auto md:h-auto flex items-center justify-center" style="max-width: 780px;">

    <img data-trigger="quote" class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"
        x-on:click="<?php echo $args['alpine_show_var']; ?> = false;"
    />

    <div class="slide w-[32rem] pb-8 grow">

        <h2 class="font-bold font-poppins text-20 mb-4"><?php echo $args['heading']; ?></h2>

        <p class="text-18"><?php echo $args['paragraph']; ?></p>

    </div>


    </div>
</div>
