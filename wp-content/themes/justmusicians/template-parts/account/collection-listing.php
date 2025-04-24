
<div class="py-4 relative flex flex-row items-start gap-3 md:gap-6 relative border-b border-black/20 last:border-none">

    <div class="w-24 md:w-32 shrink-0">
        <div class="bg-yellow-light aspect-4/3">
            <img class="w-full h-full object-cover" src="<?php echo $args['thumbnail_url']; ?>" />
        </div>
    </div>

    <div class="py-2 flex flex-col gap-y-2">
        <div class="flex flex-row">
            <h2 class="text-18 sm:text-20 font-semibold"><a href="#"><?php echo $args['name']; ?></a></h2>
        </div>
        <div class="flex items-center gap-1 flex-wrap">
        </div>
    </div>

    <button class="absolute p-2 top-2 right-2 opacity-50 hover:opacity-100">
        <a href="/favorites"><img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/pencil-solid.svg'; ?>" /></a>
    </button>
    <?php if ($args['allow_delete']) { ?>
        <button class="absolute p-2 top-10 right-2 opacity-50 hover:opacity-100"
            hx-delete="/wp-html/v1/collections/<?php echo $args['post_id']; ?>"
            hx-confirm="Are you sure you want to delete this collection?"
            hx-target="#result"
        >
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/trash.svg'; ?>" />
        </button>
    <?php } ?>


</div>

