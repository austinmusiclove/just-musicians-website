<label class="custom-checkbox has-disabled:text-grey" <?php if (!empty($args['x-show'])) { echo ' x-show="' . $args['x-show'] . '"'; } ?> >
<input type="checkbox"
    <?php
        if (!empty($args['on_change_event'])) { echo 'x-on:change="$dispatch(\'' . $args['on_change_event'] . '\')"'; }
        if (!empty($args['name'])) {
            echo ' name="' . $args['name'];
            if (!empty($args['is_array']) and $args['is_array']) {
                echo '[]';
            }
            echo '"';
        }
        if (!empty($args['input_id'])) { echo ' id="' . $args['input_id'] . '"'; }
        if (!empty($args['value'])) { echo ' value="' . $args['value'] . '"'; }
        if (!empty($args['x-model'])) { echo ' x-model="' . $args['x-model'] . '"'; }
        if (!empty($args['x-ref'])) { echo ' x-ref="' . $args['x-ref'] . '"'; }
        if (!empty($args['x-disabled'])) { echo ' x-bind:disabled="' . $args['x-disabled'] . '"'; }
        if ($args['checked']) { echo ' checked'; }
    ?>
    />
<span class="checkmark"></span><?php echo $args['label']; ?></label>
