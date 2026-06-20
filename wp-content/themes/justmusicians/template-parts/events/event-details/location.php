<?php
$address_line_1 = $args['address_line_1'] ?? '';
$address_line_2 = $args['address_line_2'] ?? '';
$city           = $args['city'] ?? '';
$state          = $args['state'] ?? '';
$zip_code       = $args['zip_code'] ?? '';

$has_location = $address_line_1 || $address_line_2 || $city || $state || $zip_code;
?>

<div class="flex items-center gap-1">
    <img style="height: 1rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
    <?php if ($has_location) { ?>
        <span class="text-16">
            <?php
            $parts = [];

            $address = trim($address_line_1 . ' ' . $address_line_2);
            if ($address) $parts[] = $address;

            $city_part = $city;
            if ($state) $city_part .= ($city_part ? ', ' : '') . $state;
            if ($zip_code) $city_part .= ' ' . $zip_code;
            if ($city_part) $parts[] = $city_part;

            echo esc_html(implode(', ', $parts));
            ?>
        </span>
    <?php } else { ?>
        <span class="text-16 text-black/50">Location not specified</span>
    <?php } ?>
</div>
