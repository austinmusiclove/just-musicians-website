<div class="flex flex-col gap-4">

    <div class="sidebar-module rounded overflow-hidden bg-white">
        <h3 class="text-22 font-bold py-2">Musician Compensation</h3>
        <div class="py-4 flex flex-col gap-4">

            <!-- Pay per gig -->
            <?php echo get_template_part('template-parts/stats/stat-card', '', [
                'title'    => 'Average Pay Per Gig',
                'stat'     => '$' . round((float) get_field('_average_earnings')),
                'sub_text' => 'Based on 11 gigs',
                'icon'     => 'music.svg',
            ]); ?>

            <!-- Pay per performer per hour -->
            <?php echo get_template_part('template-parts/stats/stat-card', '', [
                'title'    => 'Average Pay Per Hour',
                'stat'     => '$' . round((float) get_field('_average_earnings_per_hour')) . '/hr',
                'sub_text' => 'Average performer count is 4',
                'icon'     => 'clock.svg',
            ]); ?>

            <!-- Payment Speed -->
            <?php echo get_template_part('template-parts/stats/stat-card-bar-chart', '', [
                'title'    => 'Payment Speed',
                'stat'     => 'Same Day',
                'sub_text' => '',
                'icon'     => 'bolt.svg',
                'index_axis' => 'x',
                'chart_id'   => 'payment_speed_chart',
                'data'       => [0, 8, 2, 0, 0, 1, 0],
                'colors'     => ["#DDD7BC", "#DDD7BC", "#DDD7BC", "#DDD7BC", "#DDD7BC", "#DDD7BC", "#DDD7BC"],
                'labels'     => [
                    'Before the gig',
                    'Same Day',
                    'Within 3 days',
                    'Within a week',
                    'Within 2 weeks',
                    'Within 30 days',
                    'Never got paid',
                ],
            ]); ?>

            <!-- Payment Method -->
            <?php echo get_template_part('template-parts/stats/stat-card-bar-chart', '', [
                'title'       => 'Payment Method',
                'stat'        => 'Cash',
                'sub_text'    => '',
                'icon'        => 'money-bill.svg',
                'index_axis'  => 'x',
                'chart_id'    => 'payment_method_chart',
                'data'       => [9, 1, 0, 1, 0, 0, 0],
                'colors'     => ["#DDD7BC", "#DDD7BC", "#DDD7BC", "#DDD7BC", "#DDD7BC", "#DDD7BC"],
                'labels'     => [
                    'Cash',
                    'Check',
                    'Direct Deposit',
                    'Zelle',
                    'Venmo/Cash App',
                    'PayPal',
                ],
            ]); ?>

        </div>
    </div>
</div>
