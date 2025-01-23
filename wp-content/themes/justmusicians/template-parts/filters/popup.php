<div data-popup="filter" class="popup-wrapper hidden p-18 w-screen h-screen fixed top-0 left-0 z-30 flex items-center justify-center">
    <div data-trigger="filter" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>

    <div class="bg-white relative pt-20 px-20 pb-32" style="max-width: 780px;">

    <img data-trigger="filter" class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" />

    <div class="grid grid-cols-4 gap-y-4 gap-x-10">
    <?php 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Acoustic'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Blues'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Bluegrass'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Cover Band'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Country'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'DJ/Producer'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Electronic/EDM'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Experimental'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Folk'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Gospel Choir'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Indie'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Jazz Band'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Latin'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Metal'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Orchestra'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Pop'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Punk'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Reggae'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'R&B/Soul'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Ska'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Solo Artist'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Tribute Band'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Wedding Band'
        )); 
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'World Music'
        )); 
    ?>
    </div>

    <button type="submit" class="bg-navy absolute bottom-10 right-10 shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-5 py-3">Update</button>

    </div>
</div>