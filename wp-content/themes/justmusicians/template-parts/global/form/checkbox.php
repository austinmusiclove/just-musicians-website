<?php
    // Unique id so the sibling <label for> can reference the input (no aria-label needed).
    $input_id = !empty($args['input_id']) ? $args['input_id'] : 'cb_' . uniqid();

    $input_attrs = '';
    if (!empty($args['on_change_event'])) { $input_attrs .= ' x-on:change="$dispatch(\'' . $args['on_change_event'] . '\')"'; }
    if (!empty($args['name'])) {
        $input_attrs .= ' name="' . $args['name'];
        if (!empty($args['is_array']) and $args['is_array']) { $input_attrs .= '[]'; }
        $input_attrs .= '"';
    }
    $input_attrs .= ' id="' . esc_attr($input_id) . '"';
    if (!empty($args['value']))      { $input_attrs .= ' value="' . $args['value'] . '"'; }
    if (!empty($args['x-model']))    { $input_attrs .= ' x-model="' . $args['x-model'] . '"'; }
    if (!empty($args['x-ref']))      { $input_attrs .= ' x-ref="' . $args['x-ref'] . '"'; }
    if (!empty($args['x-disabled'])) { $input_attrs .= ' x-bind:disabled="' . $args['x-disabled'] . '"'; }
    if (!empty($args['checked']))    { $input_attrs .= ' checked'; }
    if (!empty($args['preload']))    { $input_attrs .= ' preload="always preload-filter"'; }

    $x_show = !empty($args['x-show']) ? ' x-show="' . $args['x-show'] . '"' : '';
?>

<div class="custom-checkbox has-disabled:text-grey"<?php echo $x_show; ?>
    <?php if (!empty($args['preload'])) { ?>
    x-on:mouseenter.debounce="$el.querySelector('input').dispatchEvent(new CustomEvent('preload-filter'))"
    x-on:mousedown="$el.querySelector('input').dispatchEvent(new CustomEvent('preload-filter'))"
    <?php } ?>
    x-on:click="if (!$event.target.closest('input, label')) { $el.querySelector('input').click(); }"
>
    <input type="checkbox"<?php echo $input_attrs; ?> />
    <span class="checkmark"></span>
    <label for="<?php echo esc_attr($input_id); ?>"><?php echo $args['label']; ?></label>
</div>
