
<div class="py-4 relative flex flex-row items-start gap-3 md:gap-6 relative border-b border-black/20 last:border-none"
    <?php if ($args['last'] and !$args['is_last_page']) { // infinite scroll; include this on the last result of the page as long as it is not the final page ?>
    hx-get="/wp-html/v1/inquiries/?page=<?php echo $args['next_page']; ?>"
    hx-trigger="revealed once"
    hx-swap="beforeend"
    hx-target="#results"
    hx-indicator="#spinner"
    <?php } ?>
>

    <div class="w-24 md:w-32 shrink-0">
        <?php if (count($args['thumbnail_urls']) >= 4) { ?>
            <div class="bg-yellow-light aspect-4/3 grid grid-cols-2 grid-rows-2 gap-0">
                <img class="w-full h-full object-cover" src="<?php echo $args['thumbnail_urls'][0]; ?>" />
                <img class="w-full h-full object-cover" src="<?php echo $args['thumbnail_urls'][1]; ?>" />
                <img class="w-full h-full object-cover" src="<?php echo $args['thumbnail_urls'][2]; ?>" />
                <img class="w-full h-full object-cover" src="<?php echo $args['thumbnail_urls'][3]; ?>" />
            </div>
        <?php } else if (count($args['thumbnail_urls']) >= 1) { ?>
            <div class="bg-yellow-light aspect-4/3">
                <img class="w-full h-full object-cover" src="<?php echo $args['thumbnail_urls'][0]; ?>" />
            </div>
        <?php } else { ?>
            <div class="bg-yellow-light aspect-4/3">
                <img class="w-full h-full object-cover" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp'; ?>" />
            </div>
        <?php } ?>
    </div>

    <div class="py-2 flex flex-col gap-y-2">
        <div class="flex flex-row">
            <a href="<?php echo $args['permalink']; ?>"><h2 class="text-18 sm:text-20 font-semibold cursor-pointer"><?php echo $args['subject']; ?></h2></a>
        </div>
        <div class="flex items-center gap-1 flex-wrap">
            <?php $num_listings_label = $args['num_listings'] == 1 ? 'Quote Requested' : 'Quotes Requested'; ?>
            <p class="text-14"><?php echo $args['num_listings'] . ' ' . $num_listings_label; ?></p>
        </div>
    </div>

    <a href="<?php echo $args['permalink']; ?>">
        <button class="absolute p-2 top-2 right-2 opacity-50 hover:opacity-100">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/pencil-solid.svg'; ?>" />
        </button>
    </a>


</div>

