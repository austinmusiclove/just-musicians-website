
<div class="py-4 relative flex flex-row items-start gap-3 md:gap-6 relative border-b border-black/20 last:border-none"
    x-data="{ showListing: true }"
    x-show="showListing" x-cloak
    x-on:hide-listing="showListing = false;"
>

    <div class="w-24 md:w-32 shrink-0">
        <div class="bg-yellow-light aspect-4/3">
            <img class="w-full h-full object-cover" src="<?php echo $args['thumbnail_url']; ?>" />
        </div>
    </div>

    <div class="py-2 flex flex-col gap-y-2">
        <div class="flex flex-row">
            <h2 class="text-18 sm:text-20 font-semibold"><a href="/listing-form/?lid=<?php echo $args['post_id']; ?>"><?php echo $args['name']; ?></a></h2>
        </div>
        <div class="flex items-center gap-1 flex-wrap">
            <?php foreach((array) $args['genres'] as $genre) { ?>
            <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-50 hover:bg-yellow-light cursor-pointer inline-block"><?php echo $genre; ?></span><?php
            } ?>
        </div>
    </div>
    <a href="/listing-form/?lid=<?php echo $args['post_id']; ?>">
        <button class="absolute p-2 top-2 right-2 opacity-50 hover:opacity-100">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/pencil-solid.svg'; ?>" />
        </button>
    </a>
    <button class="absolute p-2 top-10 right-2 opacity-50 hover:opacity-100"
        hx-delete="<?php echo site_url('/wp-html/v1/listings/' . $args['post_id']); ?>"
        hx-confirm="Are you sure you want to delete this listing?"
        hx-target="#result-<?php echo $args['post_id']; ?>"
    >
        <span id="result-<?php echo $args['post_id']; ?>"></span>
        <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/trash.svg'; ?>" />
    </button>


</div>
