<label class="custom-checkbox">
    <input type="checkbox" x-on:change="$dispatch('filterupdate');"
    <?php
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
    ?>
    />
<span class="checkmark"></span><?php echo $args['label']; ?></label>
