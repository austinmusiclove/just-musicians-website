<canvas
    id="<?php echo $args['chart_id']; ?>"
    x-data="{
        'chartId':   '<?php echo $args['chart_id']; ?>',
        'indexAxis': '<?php echo $args['index_axis']; ?>',
        'labels':     <?php echo clean_arr_for_doublequotes($args['labels']); ?>,
        'data':       <?php echo clean_arr_for_doublequotes($args['data']); ?>,
        'colors':     <?php echo clean_arr_for_doublequotes($args['colors']); ?>,
    }"
    x-init="initBarChart(chartId, indexAxis, labels, data, colors)"
>
</canvas>
