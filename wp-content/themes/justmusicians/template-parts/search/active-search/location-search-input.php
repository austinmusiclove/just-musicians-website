<div class="<?php echo $args['container_class']; ?>">

    <img class="<?php echo $args['image_class']; ?>" src="<?php echo get_template_directory_uri() . '/lib/images/icons/' . $args['image_file']; ?>" />

    <input
        id="<?php echo $args['id']; ?>"
        class="<?php echo $args['input_class']; ?>"
        type="text"
        name="<?php echo $args['input_name']; ?>"
        title="Enter a US or Canada postal code (ex. 78701, A1A)."
        placeholder="<?php echo $args['placeholder']; ?>"
        autocomplete="<?php echo $args['autocomplete'] ?? 'off'; ?>"
        <?php if ($args['required']) { echo 'required'; } ?>

        hx-get="<?php echo site_url($args['htmx_path']); ?>"
        hx-trigger="input changed delay:300ms"
        hx-target="#<?php echo $args['id']; ?>-target"
        hx-indicator="#<?php echo $args['spinner_id']; ?>"
        hx-vals='{"update_func": "<?php echo $args['update_func']; ?>"}'

        x-model="<?php echo $args['input_var']; ?>"
        x-on:focus="<?php echo $args['show_var']; ?> = true; <?php echo $args['input_var']; ?> = '';"
        x-on:click.away="<?php echo $args['show_var']; ?> = false; <?php echo $args['input_var']; ?> = <?php echo $args['selected_var']; ?>;"
        <?php if (isset($args['x_class'])) { echo ':class="' . $args['x_class'] . '"'; } ?>
    />

    <div id="<?php echo $args['id']; ?>-target" x-show="<?php echo $args['show_var']; ?>" x-cloak>
        <?php echo get_template_part('template-parts/search/active-search/location-search-state-1', '', [ 'message' => $args['state_1_msg'] ]); ?>
    </div>

</div>
