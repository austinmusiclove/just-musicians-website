<div data-search-state="desktop-2" class="absolute top-full left-0 w-full px-4 py-4 bg-white flex flex-col shadow-md rounded-sm">
<!-- Categories and genres -->
<?php
foreach((array) $args['categories'] as $category) {
?>
    <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="#" x-on:click="searchInput = '<?php echo str_replace("'", "\'", $category); ?>'; showSearchOptions = false;" >
        <?php echo $category; ?>
    </a>
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
