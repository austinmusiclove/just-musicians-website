<div id="ensemble-size" class="border-b border-black/20 mb-6 pb-6 last:mb-0 last:pb-0 last:border-b-0" hx-swap-oob="outerHTML">
    <h3 class="font-bold text-18 mb-3">Ensemble Size</h3>
    <div class="flex items-center gap-1 flex-wrap">


        <div class="">
            <!-- Min Range Slider -->
            <div>
            <label class="mb-1">Min: <span x-text="parseInt(<?php echo $args['min_input_x_model']; ?>) == 10 ? '10+' : <?php echo $args['min_input_x_model']; ?>"></span></label>
                <input type="range" class="w-full accent-navy" min="1" max="10"
                    min="1"
                    max="10"
                    name="<?php echo $args['min_input_name']; ?>"
                    value="<?php echo $args['min_value']; ?>"
                    x-model="<?php echo $args['min_input_x_model']; ?>"
                    x-ref="<?php echo $args['min_input_x_ref']; ?>"
                    x-on:input="
                    if ($el.value > parseInt(<?php echo $args['max_input_x_model']; ?>)) {
                        $el.value = <?php echo $args['max_input_x_model']; ?>;
                        <?php echo $args['min_input_x_model']; ?> = <?php echo $args['max_input_x_model']; ?>;
                    }"
                    x-on:change="$nextTick(() => $dispatch('<?php echo $args["on_change_event"]; ?>'));"
                />
            </div>

            <!-- Max Range Slider -->
            <div>
                <label class="mb-1">Max: <span x-text="parseInt(<?php echo $args['max_input_x_model']; ?>) == 10 ? '10+' : <?php echo $args['max_input_x_model']; ?>"></span></label>
                <input type="range" class="w-full accent-navy"
                    min="1"
                    max="10"
                    name="<?php echo $args['max_input_name']; ?>"
                    value="<?php echo $args['max_value']; ?>"
                    x-model="<?php echo $args['max_input_x_model']; ?>"
                    x-ref="<?php echo $args['max_input_x_ref']; ?>"
                    x-on:input="
                    if ($el.value < parseInt(<?php echo $args['min_input_x_model']; ?>)) {
                        $el.value = <?php echo $args['min_input_x_model']; ?>;
                        <?php echo $args['max_input_x_model']; ?> = <?php echo $args['min_input_x_model']; ?>;
                    }"
                    x-on:change="$nextTick(() => $dispatch('<?php echo $args["on_change_event"]; ?>'));"
                />
            </div>
        </div>


    </div>
</div>
