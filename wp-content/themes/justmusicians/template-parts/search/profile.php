
<div class="py-4 relative flex items-start gap-7 relative">

    <button class="absolute top-7 right-3 opacity-60 hover:opacity-100 hover:scale-105">
        <img class="h-6 w-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/favorite.svg'; ?>" />
    </button>

    <div class="bg-yellow-light w-56 aspect-4/3 shrink-0">
        <img class="w-full h-full object-cover" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/' . $args['slug']; ?>" />
    </div>

    <div class="py-2 flex flex-col gap-y-2">
        <h2 class="text-22 font-bold"><a href="#"><?php echo $args['name']; ?></a></h2>
        <span class="text-14 flex items-center">
            <img class="h-4 mr-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
            <?php echo $args['location']; ?>
        </span>
        <p class="text-14"><?php echo $args['description']; ?></p>
        <div class="flex items-center gap-1">
            <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-light hover:bg-yellow cursor-pointer inline-block"><?php echo $args['genre_1']; ?></span>
            <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-light hover:bg-yellow cursor-pointer inline-block"><?php echo $args['genre_2']; ?></span>
        </div>
        <div class="flex items-center gap-1">
            <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/instagram.svg'; ?>" />
            <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/facebook.svg'; ?>" />
            <img class="h-4 opacity-20 hover:opacity-60 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/social/youtube.svg'; ?>" />
        </div>
    </div>

    <button class="absolute right-3 bottom-3 hover:bg-yellow-light bg-yellow px-3 py-4 rounded-sm font-sun-motter text-12 inline-block">Request Quote</button>
</div>