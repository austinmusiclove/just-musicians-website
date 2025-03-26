<div data-search-state="mobile-2" class="w-full flex flex-col gap-8">

    <div>
        <!-- Categories -->
        <?php foreach(array_map('stripslashes', (array) $args['categories']) as $term) { ?>
            <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="<?php echo get_site_url() . "/?qcategory=" . $term; ?>" x-on:click="showSearchOptions = false;" >
                <?php echo $term; ?>
            </a>
        <?php } ?>

        <!-- Genres -->
        <?php foreach(array_map('stripslashes', (array) $args['genres']) as $term) { ?>
            <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="<?php echo get_site_url() . "/?qgenre=" . $term; ?>" x-on:click="showSearchOptions = false;" >
                <?php echo $term; ?>
            </a>
        <?php } ?>

        <!-- Subgenres -->
        <?php foreach(array_map('stripslashes', (array) $args['subgenres']) as $term) { ?>
            <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="<?php echo get_site_url() . "/?qsubgenre=" . $term; ?>" x-on:click="showSearchOptions = false;" >
                <?php echo $term; ?>
            </a>
        <?php } ?>

        <!-- Instrumentatoins -->
        <?php foreach(array_map('stripslashes', (array) $args['instrumentations']) as $term) { ?>
            <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="<?php echo get_site_url() . "/?qinstrumentation=" . $term; ?>" x-on:click="showSearchOptions = false;" >
                <?php echo $term; ?>
            </a>
        <?php } ?>

        <!-- Settings -->
        <?php foreach(array_map('stripslashes', (array) $args['settings']) as $term) { ?>
            <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="<?php echo get_site_url() . "/?qsetting=" . $term; ?>" x-on:click="showSearchOptions = false;" >
                <?php echo $term; ?>
            </a>
        <?php } ?>

        <!-- Listings -->
        <?php foreach((array) $args['listings'] as $listing) {
            $search_term = stripslashes($listing['name']); ?>
            <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="<?php echo get_site_url() . "/?qsearch=" . $search_term; ?>" x-on:click="showSearchOptions = false;" >
                <div class="w-6 aspect-square shrink-0">
                    <img class="w-full h-full object-cover" src="<?php echo $listing['thumbnail_url']; ?>" />
                </div>
                <?php echo $listing['name']; ?>
            </a>
        <?php } ?>
    </div>


</div>
