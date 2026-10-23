<?php

$heading  = $args['heading'] ?? '';
$features = $args['features'] ?? [];

if (empty($features)) {
    return;
}

?>

<div>

    <?php if ($heading !== '') { ?>
    <h3 class="font-bold text-16 mb-2"><?php echo esc_html($heading); ?></h3>
    <?php } ?>

    <div class="flex flex-col items-start gap-1">

        <?php foreach ($features as $feature) {
            $label      = $feature['label'] ?? '';
            $alpine_var = $feature['alpine_var'] ?? '';

            if ($label === '' || $alpine_var === '') {
                continue;
            } ?>

            <div class="flex items-center gap-2">
                <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/check.svg" x-show="<?php echo esc_attr($alpine_var); ?>" x-cloak />
                <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/xmark.svg" x-show="!<?php echo esc_attr($alpine_var); ?>" x-cloak />
                <span class="text-16"><?php echo esc_html($label); ?></span>
            </div>

        <?php } ?>

    </div>

</div>
