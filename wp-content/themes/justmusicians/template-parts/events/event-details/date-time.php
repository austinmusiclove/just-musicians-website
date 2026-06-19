<?php
$start_date = $args['start_date'] ?? '';
$end_date   = $args['end_date'] ?? '';
$start_time = $args['start_time'] ?? '';
$end_time   = $args['end_time'] ?? '';

$has_date = (bool) $start_date;
$date_line = '';
if ($start_date) {
    $start_ts = strtotime($start_date);
    $date_line = gmdate('M j, Y', $start_ts);
    if ($end_date) {
        $end_ts = strtotime($end_date);
        $end_display = gmdate('M j, Y', $end_ts);
        if ($start_time) $date_line .= ' ' . gmdate('g:i A', strtotime($start_time));
        if ($end_time) $end_display .= ' ' . gmdate('g:i A', strtotime($end_time));
        $date_line .= ' – ' . $end_display;
    } else {
        if ($start_time) {
            $date_line .= ' • ' . gmdate('g:i A', strtotime($start_time));
            if ($end_time) $date_line .= ' – ' . gmdate('g:i A', strtotime($end_time));
        }
    }
}
?>
<div>
    <span class="text-12 text-black/50 font-semibold">Date & Time</span>
    <?php if ($has_date) { ?>
        <p class="text-14"><?php echo esc_html($date_line); ?></p>
    <?php } else { ?>
        <p class="text-14 text-black/50">Not specified</p>
    <?php } ?>
</div>
