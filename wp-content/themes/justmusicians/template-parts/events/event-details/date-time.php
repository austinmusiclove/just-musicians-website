<?php
$start_date = $args['start_date'] ?? '';
$end_date   = $args['end_date'] ?? '';
$start_time = $args['start_time'] ?? '';
$end_time   = $args['end_time'] ?? '';

// Date line
$date_line = '';
if ($start_date && $end_date) {
    $date_line = gmdate('M j, Y', strtotime($start_date)) . ' – ' . gmdate('M j, Y', strtotime($end_date));
} elseif ($start_date) {
    $date_line = gmdate('M j, Y', strtotime($start_date));
} elseif ($end_date) {
    $date_line = '? – ' . gmdate('M j, Y', strtotime($end_date));
}

// Time line
$time_line = '';
if ($start_time && $end_time) {
    $time_line = gmdate('g:i A', strtotime($start_time)) . ' – ' . gmdate('g:i A', strtotime($end_time));
} elseif ($start_time) {
    $time_line = gmdate('g:i A', strtotime($start_time));
} elseif ($end_time) {
    $time_line = '? – ' . gmdate('g:i A', strtotime($end_time));
}
?>
<div class="flex flex-col gap-2">
    <div class="flex items-center gap-1">
        <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/calendar.svg" />
        <?php if ($date_line) { ?>
            <span class="text-16"><?php echo esc_html($date_line); ?></span>
        <?php } else { ?>
            <span class="text-16 text-black/50">Date not specified</span>
        <?php } ?>
    </div>

    <div class="flex items-center gap-1">
        <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/clock.svg" />
        <?php if ($time_line) { ?>
            <span class="text-16"><?php echo esc_html($time_line); ?></span>
        <?php } else { ?>
            <span class="text-16 text-black/50">Time not specified</span>
        <?php } ?>
    </div>
</div>
