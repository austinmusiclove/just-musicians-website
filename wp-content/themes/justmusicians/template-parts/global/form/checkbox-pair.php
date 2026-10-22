<div class="p-4 mt-4 mb-4 bg-grey-light-2 border border-grey-light-50 flex items-center gap-x-4 gap-y-2 rounded-sm flex-wrap">
<?php
    get_template_part('template-parts/global/form/checkbox', '', [
        'label'        => $args['label_1'],
        'value'        => '1',
        'x-model'      => $args['x_model_1'] ?? '',
        'bind-checked' => $args['x_model_1'] ?? '',
    ]);
    if (isset($args['x_model_1'])) { ?><input type="hidden" name="<?php echo $args['name_1']; ?>" x-bind:value="<?php echo $args['x_model_1']; ?> ? '1' : '0'" /><?php }
    if (isset($args['tooltip_1'])) { get_template_part('template-parts/global/tooltips/tooltip', '', [ 'tooltip' => $args['tooltip_1'] ]); }

    get_template_part('template-parts/global/form/checkbox', '', [
        'label'        => $args['label_2'],
        'value'        => '1',
        'x-model'      => $args['x_model_2'] ?? '',
        'bind-checked' => $args['x_model_2'] ?? '',
    ]);
    if (isset($args['x_model_2'])) { ?><input type="hidden" name="<?php echo $args['name_2']; ?>" x-bind:value="<?php echo $args['x_model_2']; ?> ? '1' : '0'" /><?php }
    if (isset($args['tooltip_2'])) { get_template_part('template-parts/global/tooltips/tooltip', '', [ 'tooltip' => $args['tooltip_2'] ]); }
?>
</div>
