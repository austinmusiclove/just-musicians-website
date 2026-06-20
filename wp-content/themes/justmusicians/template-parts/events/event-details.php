<?php
$details       = $args['details'] ?? '';
$budget        = $args['budget'] ?? '';
$compensation  = $args['compensation'] ?? '';
$request_quote = $args['request_quote'] ?? false;
$request_draw  = $args['request_draw'] ?? false;
$genres        = $args['genres'] ?? [];
$ensemble_size = $args['ensemble_size'] ?? [];

?>
<div class="pt-4" x-show="showEventDetails" x-cloak>
    <div class="flex flex-col gap-4">

        <?php echo get_template_part('template-parts/events/event-details/date-time', '', [
            'start_date' => $args['start_date'] ?? '',
            'end_date'   => $args['end_date'] ?? '',
            'start_time' => $args['start_time'] ?? '',
            'end_time'   => $args['end_time'] ?? '',
        ]); ?>

        <?php echo get_template_part('template-parts/events/event-details/location', '', [
            'address_line_1' => $args['address_line_1'] ?? '',
            'address_line_2' => $args['address_line_2'] ?? '',
            'city'           => $args['city'] ?? '',
            'state'          => $args['state'] ?? '',
            'zip_code'       => $args['zip_code'] ?? '',
        ]); ?>

        <!-- Genres -->
        <div class="flex items-start gap-1">
            <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/music.svg" />
            <?php if (!empty($genres)) { ?>
                <div class="flex flex-wrap items-center gap-1">
                    <?php foreach ($genres as $genre) { ?>
                        <span class="bg-yellow-light px-2 py-0.5 rounded-full font-bold text-12"><?php echo esc_html($genre); ?></span>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p class="text-16 text-black/50">No genres specified</p>
            <?php } ?>
        </div>

        <!-- Ensemble Size -->
        <div class="flex items-start gap-1">
            <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/user-group.svg" />
            <?php if ($ensemble_size) { ?>
                <span class="text-16 v"><?php echo implode(', ', $ensemble_size); ?></span>
            <?php } else { ?>
                <span class="text-16 text-black/50">Any Ensemble Size</span>
            <?php } ?>
        </div>

        <!-- Details -->
        <div>
            <h3 class="font-bold text-16 mb-2">Details</h3>
            <?php if ($details) { ?>
                <p class="text-16"><?php echo esc_html($details); ?></p>
            <?php } else { ?>
                <p class="text-16 text-black/50">Not specified</p>
            <?php } ?>
        </div>

        <!-- Compensation -->
        <div>
            <h3 class="font-bold text-16 mb-2">Compensation Details</h3>
            <div class="flex items-center gap-1">
                <!--<img style="height: 1rem" src="<?php //echo get_template_directory_uri(); ?>/lib/images/icons/money-bill.svg" />-->
                <?php if ($budget) { ?>
                    <span class="text-16">Live Music Budget: $<?php echo esc_html(number_format((float) $budget)); ?></span>
                <?php } else { ?>
                    <span class="text-16 text-black/50">Budget not specified</span>
                <?php } ?>
            </div>
            <?php if ($compensation) { ?>
                <span class="text-16"><?php echo esc_html($compensation); ?></span>
            <?php } else { ?>
                <span class="text-16 text-black/50">Compensation not specified</span>
            <?php } ?>
        </div>

        <!-- Applicant Requirements -->
        <div>
            <h3 class="font-bold text-16 mb-2">Applicant Requirements</h3>
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <?php if ($request_quote) { ?>
                        <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/check.svg" />
                    <?php } else { ?>
                        <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/xmark.svg" />
                    <?php } ?>
                    <span class="text-16">Request Quote</span>
                </div>
                <div class="flex items-center gap-2">
                    <?php if ($request_draw) { ?>
                        <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/check.svg" />
                    <?php } else { ?>
                        <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/xmark.svg" />
                    <?php } ?>
                    <span class="text-16">Request Draw</span>
                </div>
            </div>
        </div>

        <!-- Edit Button -->
        <div class="pt-4">
            <a href="#" class="bg-yellow hover:bg-navy text-black hover:text-white px-6 py-3 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap inline-block">
                Edit Event
            </a>
        </div>

    </div>
</div>
