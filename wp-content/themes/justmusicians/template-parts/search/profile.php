
<div class="py-4 relative flex flex-col sm:flex-row items-start gap-3 md:gap-7 relative"
    <?php if ($args['last']) { // infinite scroll ?>
    hx-get="/wp-html/v1/listings/?page=<?php echo $args['next_page']; ?>"
    hx-trigger="revealed once"
    hx-swap="beforeend"
    hx-include="#listing-form"
    <?php } ?>
>

    <button type="button" class="absolute top-7 right-3 opacity-60 hover:opacity-100 hover:scale-105" x-on:click="showFavModal = ! showFavModal">
        <img class="h-6 w-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/favorite.svg'; ?>" />
    </button>

    <div class="w-full sm:w-56 shrink-0">
        <div class="bg-yellow-light aspect-4/3">
            <img class="w-full h-full object-cover" src="<?php echo $args['thumbnail_url']; ?>" />
        </div>
    </div>

    <div class="py-2 flex flex-col gap-y-2">
        <h2 class="text-22 font-bold"><a href="#"><?php echo $args['name']; ?></a></h2>
        <span class="text-14 flex items-center">
            <img class="h-4 mr-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
            <?php echo $args['location']; ?>
        </span>
        <p class="text-14"><?php echo $args['description']; ?></p>
        <div class="flex items-center gap-1">
            <?php foreach((array) $args['genres'] as $genre) { ?>
            <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-light hover:bg-yellow cursor-pointer inline-block"><?php echo $genre; ?></span><?php
            } ?>
        </div>
        <div class="flex items-center gap-1">
            <?php if (!empty($args['instagram_url'])) { ?>
                <a target="_blank" href="<?php echo $args['instagram_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/instagram.svg'; ?>" /></a>
            <?php } if (!empty($args['facebook_url'])) { ?>
                <a target="_blank" href="<?php echo $args['facebook_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/facebook.svg'; ?>" /></a>
            <?php } if (!empty($args['youtube_url'])) { ?>
                <a target="_blank" href="<?php echo $args['youtube_url']; ?>"><img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/youtube.svg'; ?>" /></a>
            <?php } ?>
        </div>
    </div>

    <!--<button type="button" class="sm:absolute sm:right-3 sm:bottom-3 w-full sm:w-fit hover:bg-yellow-light bg-yellow px-3 py-4 rounded-sm font-sun-motter text-12 inline-block">Send Inquiry</button>-->
</div>
