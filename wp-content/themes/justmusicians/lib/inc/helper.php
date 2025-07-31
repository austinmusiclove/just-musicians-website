<?php

function get_default_options($filter) {
    // This is the singluar place to adjust the default filter options
    // Each array does not have to have the same number of options
    $default_options = [
        'category' =>        ['Band', 'DJ', 'Solo Artist', 'Cover Band'],
        'genre' =>           ['Folk', 'Hip Hop', 'Latin', 'Soul'],
        'subgenre' =>        ['Americana', 'Punk Rock', 'Honky Tonk', '90s Covers'],
        'instrumentation' => ['Guitar', 'Vocals', 'Piano', 'Saxophone'],
        'setting' =>         ['Wedding', 'Festival', 'Hotel', 'Jazz Club'],
    ];
    return $default_options[$filter];
}

function get_checkbox_ref_string($input_name, $label) {
    $clean_label = html_entity_decode($label, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Handles html encoded characters like &amp; for &
    $cleaned =  preg_replace('/[^A-Za-z0-9]/', '', $clean_label); // remove special characters
    return 'cr' . preg_replace('/[^A-Za-z0-9]/', '', $clean_label); // append cr to the begginning to avoid having a result that starts with a number which would not work as a js variable
}

// Modifies an array so that it can be injected into doublequotes in an html attribute
// unserialize, json encode, then encode only " and '
function clean_arr_for_doublequotes($arr) {
    return html_entity_decode(htmlspecialchars(json_encode(maybe_unserialize($arr), JSON_UNESCAPED_SLASHES), ENT_QUOTES , 'UTF-8'), ENT_NOQUOTES, 'UTF-8');
}

// Modifies a string so that it can be injected into doublequotes in an html attribute
function clean_str_for_doublequotes($string) {
    return str_replace("\t", "\\t", str_replace("\r", "\\r", str_replace("\n", "\\n", str_replace("'", "\'", str_replace("\\", "\\\\", htmlspecialchars($string, ENT_COMPAT, 'UTF-8'))))));
}

function get_terms_decoded($taxonomy, $fields, $search=false, $hide_empty=false) {
    $args = [
        'taxonomy'   => $taxonomy,
        'fields'     => 'names',
        'hide_empty' => $hide_empty,
    ];
    if ($search) { $args['search'] = $search; }
    $terms = get_terms($args);
    return array_map(function($term) { return html_entity_decode($term, ENT_QUOTES | ENT_HTML5, 'UTF-8'); }, $terms);
}

function clean_url_for_display($url) {
    $url = preg_replace('#^https?://#', '', $url);
    $url = preg_replace('#^www\.#', '', $url);
    return $url;
}

function generate_calendar_grid($month, $year, $event_day, $instance) {
    $month = (int)$month;
    $year = (int)$year;
    if ($event_day != null) {
        $event_day = (int)$event_day;
    }

    // First day of the month
    $firstDayOfMonth = strtotime("$year-$month-01");
    $daysInMonth = (int)date('t', $firstDayOfMonth);

    // Day of the week the month starts on (0 = Sunday)
    $startWeekday = (int)date('w', $firstDayOfMonth);

    // Start the calendar on the Sunday before the first day
    $calendarStart = strtotime("-$startWeekday days", $firstDayOfMonth);

    // Day headers
    $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $calendarHTML = "<div class=\"grid grid-cols-7 uppercase text-center text-14 mb-4\">\n";
    foreach ($dayNames as $day) {
        $calendarHTML .= "<div class=\"day-header\">$day</div>\n";
    }
    $calendarHTML .= "</div>\n";


    // Calendar days (5 rows x 7 = 35 days)
    $calendarHTML .= "<div class=\"grid grid-cols-7\">\n";
    for ($i = 0; $i < 35; $i++) {
        $currentDate = strtotime("+$i days", $calendarStart);
        $currentMonth = (int)date('n', $currentDate);
        $displayDay = date('j', $currentDate);

        $isInMonth = $currentMonth === $month;
        $classes = 'aspect-square flex items-center justify-center relative';
        if ($event_day != null && $i == $event_day) {
            $classes .= ' bg-yellow rounded-full group';
        }
        //$classes .= $isInMonth ? '' : ' opacity-50';

        $calendarHTML .= "<div class=\"$classes\">$displayDay";
        if ($event_day != null && $i == $event_day) {
            ob_start();
            if ($instance == 'listing-page') {
                get_template_part('template-parts/global/event-tooltip', '', array());
            } else {
                get_template_part('template-parts/global/add-event-tooltip', '', array());
            }
            $calendarHTML .= ob_get_clean();
        }
        $calendarHTML .= "</div>\n";
    }
    $calendarHTML .= "</div>\n";

    return $calendarHTML;
}

function get_instagram_url_from_handle($handle) {
    $instagram_url_prefix = 'https://instagram.com/';
    return !empty($handle) ? $instagram_url_prefix . $handle : '';
}
function get_tiktok_url_from_handle($handle) {
    $tiktok_url_prefix = 'https://tiktok.com/';
    return !empty($handle) ? $tiktok_url_prefix . $handle : '';
}
function get_x_url_from_handle($handle) {
    $x_url_prefix = 'https://x.com/';
    return !empty($handle) ? $x_url_prefix . $handle : '';
}
function get_spotify_artist_url_from_id($id) {
    $spotify_artist_url_prefix = 'https://open.spotify.com/artist/';
    return !empty($id) ? $spotify_artist_url_prefix . $id : '';
}
