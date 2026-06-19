<?php
$details       = $args['details'] ?? '';
$budget        = $args['budget'] ?? '';
$compensation  = $args['compensation'] ?? '';
$request_quote = $args['request_quote'] ?? false;
$request_draw  = $args['request_draw'] ?? false;
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

        <!-- Details -->
        <div>
            <span class="text-12 text-black/50 font-semibold">Details</span>
            <?php if ($details) { ?>
                <p class="text-14"><?php echo esc_html($details); ?></p>
            <?php } else { ?>
                <p class="text-14 text-black/50">Not specified</p>
            <?php } ?>
        </div>

        <!-- Budget -->
        <div>
            <span class="text-12 text-black/50 font-semibold">Budget</span>
            <?php if ($budget) { ?>
                <p class="text-14">$<?php echo esc_html(number_format((float) $budget)); ?></p>
            <?php } else { ?>
                <p class="text-14 text-black/50">Not specified</p>
            <?php } ?>
        </div>

        <!-- Compensation -->
        <div>
            <span class="text-12 text-black/50 font-semibold">Compensation Details</span>
            <?php if ($compensation) { ?>
                <p class="text-14"><?php echo esc_html($compensation); ?></p>
            <?php } else { ?>
                <p class="text-14 text-black/50">Not specified</p>
            <?php } ?>
        </div>

        <!-- Requirements -->
        <div>
            <span class="text-12 text-black/50 font-semibold">Applicant Requirements</span>
            <ul class="list-inside text-14">
                <li>Request Quote: <?php echo $request_quote ? 'Yes' : 'No'; ?></li>
                <li>Request Draw: <?php echo $request_draw ? 'Yes' : 'No'; ?></li>
            </ul>
        </div>

    </div>
</div>
