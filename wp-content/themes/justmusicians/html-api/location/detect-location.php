<?php
// Cache-Control header so the current request's response can be cached per device
header('Cache-Control: private, max-age=60');

// Detect the visitor's location from their IP. Mirrors the fallback used in
// html-api/listings/get-listings.php so the hero and header autofill consistently.
$lat      = null;
$lng      = null;
$location_label = null;

$detected_location = function_exists('hm_get_ip_location') ? hm_get_ip_location() : null;
if ($detected_location) {
    $lat = $detected_location->lat;
    $lng = $detected_location->lon;
    $location_label = "{$detected_location->city}, {$detected_location->region}";
} else {
    $lat = 30.2672;
    $lng = -97.7431;
    $location_label = 'Austin, Texas';
}

$escaped_label = addslashes($location_label);

?>
<span x-init="$dispatch('location-detected', {'lat': '<?php echo $lat; ?>', 'lng': '<?php echo $lng; ?>', 'label': '<?php echo $escaped_label; ?>' })"></span>
