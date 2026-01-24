<section class="flex flex-col gap-5">

    <!-- Heading -->
    <div class="flex items-center gap-2">
        <img class="h-6 opacity-80" src="<?php echo get_template_directory_uri() . '/lib/images/icons/money-bill.svg'; ?>" />
        <h2 class="text-25 font-bold">Earnings</h2>
    </div>


    <fieldgroup class="block border-b border-black/20 pb-6">
        <div class="grid sm:grid-cols-3 gap-2">

            <!-- Total Earnings -->
            <?php echo get_template_part('template-parts/global/form/input-icon-currency', '', [
                'title'         => 'Total Earnings',
                'input_name'    => 'total_earnings',
                'icon_filename' => 'money-bill.svg',
                'tooltip'       => 'Tooltip info',
                'placeholder'   => '0',
                'currency'      => '$',
                'required'      => true,
            ]); ?>

            <!-- Performance Duration -->
            <?php echo get_template_part('template-parts/global/form/input-icon-number', '', [
                'title'         => 'Performance Duration',
                'input_name'    => 'minutes_performed',
                'icon_filename' => 'clock.svg',
                'tooltip'       => 'Tooltip info',
                'placeholder'   => '60',
                'min'           => '1',
                'unit_sufix'    => 'Minutes',
                'required'      => true,
            ]); ?>

            <!-- Number of Performers -->
            <?php echo get_template_part('template-parts/global/form/input-icon-number', '', [
                'title'         => 'Number of Performers',
                'input_name'    => 'total_performers',
                'icon_filename' => 'people.svg',
                'tooltip'       => 'Tooltip info',
                'placeholder'   => '1',
                'min'           => '1',
                'required'      => true,
            ]); ?>

        </div>
    </fieldgroup>

    <!-- Comp Structure -->
    <?php echo get_template_part('template-parts/global/form/dropdown', '', [
        'title'      => 'Compensation Structure',
        'input_name' => 'comp_structure',
        'options'    => [ 'Guarantee', 'Door Deal', 'Bar Deal', 'Versus Deal', 'Tips Only', 'Other', ],
        'tooltip'    => 'Tooltip text',
        'required'   => true,
    ]); ?>

    <!-- Payment Speed -->
    <?php echo get_template_part('template-parts/global/form/dropdown', '', [
        'title'      => 'When were you paid?',
        'input_name' => 'payment_speed',
        'options'    => [ 'Before the gig', 'On the day of the gig', 'Within 3 days after the gig', 'Within a week after the gig', 'Within 2 weeks after the gig', 'Within 30 days after the gig', 'Never got paid', ],
        'tooltip'    => 'Tooltip text',
        'required'   => true,
    ]); ?>

    <!-- Payment Method -->
    <?php echo get_template_part('template-parts/global/form/dropdown', '', [
        'title'      => 'How were you paid?',
        'input_name' => 'payment_method',
        'options'    => [ 'Cash', 'Check', 'Direct Deposit', 'Zelle', 'Venmo/Cash App', 'PayPal', ],
        'tooltip'    => 'Tooltip text',
        'required'   => true,
    ]); ?>


</section>
