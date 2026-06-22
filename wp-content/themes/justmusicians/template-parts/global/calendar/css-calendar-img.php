<?php
$ts = !empty($args['timestamp']) ? (int) $args['timestamp'] : 0;
$month_abbr = $ts ? gmdate('M', $ts) : '';
$day_num    = $ts ? gmdate('j', $ts) : '';
$day_abbr   = $ts ? gmdate('D', $ts) : '';
$year       = $ts ? gmdate('Y', $ts) : '';
$alpine_var = !empty($args['alpine_var']) ? $args['alpine_var'] : '';
?>
<?php if ($alpine_var) { ?>
    <div x-show="<?php echo $alpine_var; ?>"
         class="flex flex-col items-center border-2 border-black rounded-sm w-full shrink-0 bg-white shadow-black-offset">
        <span class="bg-yellow text-black text-10 font-sun-motter uppercase w-full text-center py-1 rounded-t-sm leading-tight"
              x-text="<?php echo $alpine_var; ?> ? new Date(<?php echo $alpine_var; ?> + 'T00:00:00').toLocaleDateString('en', { month: 'short', year: 'numeric' }) : ''"></span>
        <span class="text-24 font-bold text-navy pt-1 leading-tight"
              x-text="<?php echo $alpine_var; ?> ? new Date(<?php echo $alpine_var; ?> + 'T00:00:00').getDate() : ''"></span>
        <span class="text-10 text-black/50 pb-1 leading-tight"
              x-text="<?php echo $alpine_var; ?> ? new Date(<?php echo $alpine_var; ?> + 'T00:00:00').toLocaleDateString('en', { weekday: 'short' }) : ''"></span>
    </div>
<?php } elseif ($ts) { ?>
    <div class="flex flex-col items-center border-2 border-black rounded-sm w-full shrink-0 bg-white shadow-black-offset">
        <span class="bg-yellow text-black text-10 font-sun-motter uppercase w-full text-center py-1 rounded-t-sm leading-tight"><?php echo esc_html($month_abbr . ' ' . $year); ?></span>
        <span class="text-24 font-bold text-navy pt-1 leading-tight"><?php echo esc_html($day_num); ?></span>
        <span class="text-10 text-black/50 pb-1 leading-tight"><?php echo esc_html($day_abbr); ?></span>
    </div>
<?php } ?>
