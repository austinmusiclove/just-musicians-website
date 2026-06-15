<?php
$ts = !empty($args['timestamp']) ? (int) $args['timestamp'] : 0;
$month_abbr = $ts ? gmdate('M', $ts) : '';
$day_num    = $ts ? gmdate('j', $ts) : '';
$day_abbr   = $ts ? gmdate('D', $ts) : '';
?>
<?php if ($ts) { ?>
    <div class="flex flex-col items-center border border-black/20 rounded-sm w-16 shrink-0 bg-white">
        <span class="bg-red text-white text-10 font-bold uppercase w-full text-center py-0.5 rounded-t-sm leading-tight"><?php echo esc_html($month_abbr); ?></span>
        <span class="text-22 font-bold pt-0.5 leading-tight"><?php echo esc_html($day_num); ?></span>
        <span class="text-10 text-black/50 pb-0.5 leading-tight"><?php echo esc_html($day_abbr); ?></span>
    </div>
<?php } ?>
