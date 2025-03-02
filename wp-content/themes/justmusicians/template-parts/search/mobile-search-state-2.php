<div data-search-state="mobile-2" class="w-full flex flex-col gap-8">

<div>
<!-- Categories and genres -->
<?php
foreach((array) $args['categories'] as $category) {
?>
    <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="#" x-on:click="searchInput = '<?php echo str_replace("'", "\'", $category); ?>'; showSearchOptions = false;" >
        <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
        <?php echo $category; ?>
    </a>
<!--
    <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="#">
        <img class="h-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-person.svg'; ?>" />
        pop rock
    </a>
-->
<?php
}
?>

<!-- Listings -->
<?php
foreach((array) $args['listings'] as $listing) {
?>
    <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="#" x-on:click="searchInput = '<?php echo str_replace("'", "\'", $listing['name']); ?>'; showSearchOptions = false;" >
        <div class="w-6 aspect-square shrink-0">
            <img class="w-full h-full object-cover" src="<?php echo $listing['thumbnail_url']; ?>" />
        </div>
        <?php echo $listing['name']; ?>
    </a>
<?php
}
?>
</div>


</div>
