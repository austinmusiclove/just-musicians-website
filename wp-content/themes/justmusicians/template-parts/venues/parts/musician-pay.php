<?php if (get_field('_comp_report_count') >= 3) { ?>

<div class="flex flex-col gap-4">

    <div class="sidebar-module rounded overflow-hidden bg-white">
        <h3 class="text-22 font-bold py-2">Musician Compensation</h3>
        <div class="py-4 flex flex-col gap-4">

            <!-- Pay per gig -->
            <?php echo get_template_part('template-parts/stats/stat-card', '', [
                'title'    => 'Average Pay Per Gig',
                'stat'     => '$' . round((float) get_field('_average_earnings'), 2),
                'sub_text' => 'Mean average of ' . get_field('_comp_report_count') . ' gigs',
                'icon'     => 'music.svg',
            ]); ?>

            <!-- Pay per performer per hour -->
            <?php echo get_template_part('template-parts/stats/stat-card', '', [
                'title'    => 'Average Pay Per Hour',
                'stat'     => '$' . round((float) get_field('_average_earnings_per_hour'), 2) . '/hr',
                'sub_text' => 'Average ensemble size is ' . round((float) get_field('_average_ensemble_size'), 2) . ' performers',
                'icon'     => 'clock.svg',
            ]); ?>

            <!-- Payment Speed -->
            <?php
            $payment_speeds    = (array)json_decode(get_field('_payment_speed'));
            $top_payment_speed = array_search(max($payment_speeds), $payment_speeds);
            $pay_speed_data    = sort_and_fill($payment_speeds, [ 'Before the gig', 'Same day', 'Within 7 days', 'Within 30 days', 'Over 30 days later', 'Never got paid', ]);
            echo get_template_part('template-parts/stats/stat-card-bar-chart', '', [
                'title'      => 'Payment Speed',
                'stat'       => $top_payment_speed,
                'sub_text'   => '',
                'icon'       => 'bolt.svg',
                'index_axis' => 'x',
                'chart_id'   => 'payment_speed_chart',
                'data'       => array_values($pay_speed_data),
                'colors'     => array_fill(0, count($pay_speed_data), "#DDD7BC"),
                'labels'     => array_keys($pay_speed_data),
            ]); ?>

            <!-- Payment Method -->
            <?php
            $payment_methods    = (array)json_decode(get_field('_payment_method'));
            $top_payment_method = array_search(max($payment_methods), $payment_methods);
            $pay_method_data    = sort_and_fill($payment_methods, [ 'Cash', 'Check', 'ACH', 'Zelle', 'Venmo', ]);
            echo get_template_part('template-parts/stats/stat-card-bar-chart', '', [
                'title'      => 'Payment Method',
                'stat'       => $top_payment_method,
                'sub_text'   => '',
                'icon'       => 'money-bill.svg',
                'index_axis' => 'x',
                'chart_id'   => 'payment_method_chart',
                'data'       => array_values($pay_method_data),
                'colors'     => array_fill(0, count($pay_method_data), "#DDD7BC"),
                'labels'     => array_keys($pay_method_data),
            ]); ?>

        </div>
    </div>
</div>

<?php } else { ?>
    <?php echo get_template_part('template-parts/venues/parts/no-musician-pay-info', '', []); ?>
<?php } ?>
