
<div class="sidebar-module border border-black/20 rounded" x-data="{ showClassifications: false }">
    <div class="flex items-center justify-between bg-yellow-light-50 font-bold py-2 px-3 cursor-pointer" x-on:click="showClassifications = !showClassifications;">
        <h3>Classifications</h3>
        <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron.svg'; ?>" />
    </div>
    <div class="p-4 flex flex-col gap-4 max-h-96 overflow-scroll"
        x-show="showClassifications" x-collapse x-cloak
        <?php if ($args['is_preview']) { ?> x-on:click="focusElm('search-optimization-terms')" <?php } ?>
    >
        <?php if ((!empty($args['categories']) && !is_wp_error($args['categories'])) or $args['is_preview']) { ?>
        <div <?php if ($args['is_preview']) { ?> x-show="categoriesCheckboxes.length > 0" x-cloak <?php } ?> >
            <h4 class="text-16 mb-3">Categories</h4>
            <div class="flex flex-wrap items-center gap-1">
                <?php foreach ($args['categories'] as $term) { ?>
                    <span class="bg-yellow-light px-2 py-0.5 rounded-full text-12" <?php if ($args['is_preview']) { ?> x-show="categoriesCheckboxes.includes('<?php echo clean_str_for_doublequotes($term); ?>')" x-cloak <?php } ?> >
                        <?php echo $term; ?>
                    </span>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <?php if ((!empty($args['genres']) && !is_wp_error($args['genres'])) or $args['is_preview']) { ?>
        <div <?php if ($args['is_preview']) { ?> x-show="genresCheckboxes.length > 0" x-cloak <?php } ?> >
            <h4 class="text-16 mb-3">Genres</h4>
            <div class="flex flex-wrap items-center gap-1">
                <?php foreach ($args['genres'] as $term) { ?>
                    <span class="bg-yellow-light px-2 py-0.5 rounded-full text-12" <?php if ($args['is_preview']) { ?> x-show="genresCheckboxes.includes('<?php echo clean_str_for_doublequotes($term); ?>')" x-cloak <?php } ?> >
                        <?php echo $term; ?>
                    </span>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <?php if ((!empty($args['subgenres']) && !is_wp_error($args['subgenres'])) or $args['is_preview']) { ?>
        <div <?php if ($args['is_preview']) { ?> x-show="subgenresCheckboxes.length > 0" x-cloak <?php } ?> >
            <h4 class="text-16 mb-3">Subgenres</h4>
            <div class="flex flex-wrap items-center gap-1">
                <?php foreach ($args['subgenres'] as $term) { ?>
                    <span class="bg-yellow-light px-2 py-0.5 rounded-full text-12" <?php if ($args['is_preview']) { ?> x-show="subgenresCheckboxes.includes('<?php echo clean_str_for_doublequotes($term); ?>')" x-cloak <?php } ?> >
                        <?php echo $term; ?>
                    </span>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <?php if ((!empty($args['instrumentations']) && !is_wp_error($args['instrumentations'])) or $args['is_preview']) { ?>
        <div <?php if ($args['is_preview']) { ?> x-show="instCheckboxes.length > 0" x-cloak <?php } ?> >
            <h4 class="text-16 mb-3">Instrumentation</h4>
            <div class="flex flex-wrap items-center gap-1">
                <?php foreach ($args['instrumentations'] as $term) { ?>
                    <span class="bg-yellow-light px-2 py-0.5 rounded-full text-12" <?php if ($args['is_preview']) { ?> x-show="instCheckboxes.includes('<?php echo clean_str_for_doublequotes($term); ?>')" x-cloak <?php } ?> >
                        <?php echo $term; ?>
                    </span>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
        <?php if ((!empty($args['settings']) && !is_wp_error($args['settings'])) or $args['is_preview']) { ?>
        <div <?php if ($args['is_preview']) { ?> x-show="settingsCheckboxes.length > 0" x-cloak <?php } ?> >
            <h4 class="text-16 mb-3">Settings</h4>
            <div class="flex flex-wrap items-center gap-1">
                <?php foreach ($args['settings'] as $term) { ?>
                    <span class="bg-yellow-light px-2 py-0.5 rounded-full text-12" <?php if ($args['is_preview']) { ?> x-show="settingsCheckboxes.includes('<?php echo clean_str_for_doublequotes($term); ?>')" x-cloak <?php } ?> >
                        <?php echo $term; ?>
                    </span>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div> <!-- End sidebar module -->
