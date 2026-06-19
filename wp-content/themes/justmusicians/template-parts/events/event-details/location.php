<?php
$address_line_1 = $args['address_line_1'] ?? '';
$address_line_2 = $args['address_line_2'] ?? '';
$city           = $args['city'] ?? '';
$state          = $args['state'] ?? '';
$zip_code       = $args['zip_code'] ?? '';

$has_location  = $address_line_1 || $address_line_2 || $city || $state || $zip_code;
$address_lines = array_filter([$address_line_1, $address_line_2]);
$city_line     = array_filter([$city, $state, $zip_code]);
?>

<div>
    <span class="text-12 text-black/50 font-semibold">Location</span>
    <?php if ($has_location) { ?>
        <?php foreach ($address_lines as $address_line) { ?>
            <p class="text-14"><?php echo esc_html($address_line); ?></p>
        <?php } ?>
        <?php if ($city_line) { ?>
            <p class="text-14"><?php echo esc_html(implode(', ', $city_line)); ?></p>
        <?php } ?>
    <?php } else { ?>
        <p class="text-14 text-black/50">Not specified</p>
    <?php } ?>
</div>
