<div data-popup="quote" class="popup-wrapper hidden pt-28 md:pt-0 w-screen h-screen fixed top-0 left-0 z-30 flex items-center justify-center">
    <div data-trigger="quote" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>

    <div class="bg-white relative p-8 md:p-20 relative w-full h-full md:w-auto md:h-auto flex items-center justify-center" style="max-width: 780px;">

    <img data-trigger="quote" class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" />

    <?php echo get_template_part('template-parts/global/form-quote/slide-1', '', array()); ?> 
    <?php echo get_template_part('template-parts/global/form-quote/slide-2', '', array()); ?> 
    <?php echo get_template_part('template-parts/global/form-quote/slide-3', '', array()); ?> 
    <?php echo get_template_part('template-parts/global/form-quote/slide-4', '', array()); ?> 
    <?php echo get_template_part('template-parts/global/form-quote/slide-5', '', array()); ?> 


    </div>
</div>