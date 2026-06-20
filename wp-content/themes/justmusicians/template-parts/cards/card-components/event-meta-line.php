<?php

// Handles creating a formatted string for event dates, times, and location
// Expects dates in Y-m-d (2027-01-07) and times in H:i (15:00) format

$start_date = $args['start_date'] ?? '';
$end_date   = $args['end_date'] ?? '';
$start_time = $args['start_time'] ?? '';
$end_time   = $args['end_time'] ?? '';
$city       = $args['city'] ?? '';
$state      = $args['state'] ?? '';

$start_ts      = $start_date ? strtotime($start_date) : null;
$end_ts        = $end_date   ? strtotime($end_date)   : null;
$start_display = $start_ts   ? gmdate('M j, Y', $start_ts) : '';
$end_display   = $end_ts     ? gmdate('M j, Y', $end_ts)   : '';

$location_parts = array_filter([$city, $state]);
$location       = !empty($location_parts) ? implode(', ', $location_parts) : '';

if ($start_display && $end_display) {
    $start_seg = $start_display;
    if ($start_time) {
        $start_seg .= ' ' . gmdate('g:i A', strtotime($start_time));
    }
    $end_seg = $end_display;
    if ($end_time) {
        $end_seg .= ' ' . gmdate('g:i A', strtotime($end_time));
    }
    $meta_parts = array_filter([$start_seg . ' – ' . $end_seg, $location]);
} else {
    $time_display = '';
    if ($start_time) {
        $time_display = gmdate('g:i A', strtotime($start_time));
        if ($end_time) {
            $time_display .= ' – ' . gmdate('g:i A', strtotime($end_time));
        }
    }

    $date_display = $start_display;
    if ($end_display && $end_display !== $start_display) {
        $date_display .= ' – ' . $end_display;
    }

    $meta_parts = array_filter([$date_display, $time_display, $location]);
}

$meta_line = !empty($meta_parts) ? implode(' • ', $meta_parts) : '';
?>

<?php if ($meta_line) { ?>
    <div class="flex items-center gap-1 min-h-[1.5rem]">
        <p class="text-14 truncate text-black/70"><?php echo esc_html($meta_line); ?></p>
    </div>
<?php } ?>
