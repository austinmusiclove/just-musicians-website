
<div class="flex flex-col mb-4">

    <div class="py-4 relative flex flex-row items-start gap-3 md:gap-6 relative border-b border-black/20 last:border-none"
        <?php if ($args['last'] and !$args['is_last_page']) { // infinite scroll; include this on the last result of the page as long as it is not the final page ?>
        hx-get="/wp-html/v1/inquiries/?page=<?php echo $args['next_page']; ?>"
        hx-trigger="revealed once"
        hx-swap="beforeend"
        hx-target="#results"
        hx-indicator="#spinner"
        <?php } ?>
    >

        <!-- Image -->
        <div class="w-32 sm:w-44 shrink-0">
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

        <!-- Info -->
        <div class="py-2 flex flex-col gap-y-2 flex-1 min-w-0 w-full">

            <!-- Title -->
            <div class="flex flex-row">
                <a href="<?php echo site_url('/messages/?iid=' . $args['post_id']); ?>">
                    <h2 class="text-18 sm:text-20 font-semibold cursor-pointer"><?php echo $args['subject']; ?></h2>
                </a>
            </div>

            <!-- Num quotes requested -->
            <div class="flex items-center gap-1 flex-wrap">
                <?php $num_listings_label = $args['num_listings'] == 1 ? 'listing' : 'listings'; ?>
                <p class="text-14"><?php echo $args['num_listings'] . ' ' . $num_listings_label; ?> invited to respond</p>
            </div>

            <!-- Details -->
            <div class="flex items-center gap-1 min-h-[1.5rem]">
                <p class="text-14 truncate"><?php echo $args['details']; ?></p>
            </div>

            <!-- See Responses -->
            <div class="hidden sm:block"><a href="<?php echo site_url('/messages/?iid=' . $args['post_id']); ?>">
                <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-2 rounded-sm font-sun-motter text-14 inline-block w-fit">See Responses</button>
            </a></div>

        </div>


    </div>

    <!-- See Responses -->
    <div class="block sm:hidden"><a href="<?php echo site_url('/messages/?iid=' . $args['post_id']); ?>">
        <button type="button" class="hover:bg-yellow-light bg-yellow px-3 py-2 rounded-sm font-sun-motter text-14 inline-block w-full">See Responses</button>
    </a></div>

</div>
