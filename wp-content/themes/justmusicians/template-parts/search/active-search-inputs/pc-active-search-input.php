<div class="relative">

    <img class="h-4 absolute bottom-3 left-3 opacity-60" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />

    <input class="has-icon"
        type="text" id="<?php echo $args['edit-event-zip']; ?>" name="pc_search"
        autocomplete="postal-code-disabled" required
        placeholder="Postal Code"
        title="Enter a US or Canada postal code (ex. 78701, A1A)."
        x-model="<?php echo $args['input_var']; ?>"
        x-on:focus="<?php echo $args['show_var']; ?> = true; <?php echo $args['input_var']; ?> = '';"
        x-on:click.away="<?php echo $args['show_var']; ?> = false; <?php echo $args['input_var']; ?> = <?php echo $args['selected_var']; ?>;"
        hx-get="<?php echo site_url('/wp-html/v1/location-search-options-pc/'); ?>"
        hx-trigger="input changed delay:300ms"
        hx-target="#<?php echo $args['edit-event-zip']; ?>-target"
        hx-indicator="#<?php echo $args['spinner_id']; ?>"
        hx-vals='{"update_func": "<?php echo $args['update_func']; ?>"}'
    />

    <div id="<?php echo $args['edit-event-zip']; ?>-target" x-show="<?php echo $args['show_var']; ?>" x-cloak>
        <?php echo get_template_part('template-parts/search/active-search/lf-location-search-state-1', '', array()); ?>
    </div>

</div>
