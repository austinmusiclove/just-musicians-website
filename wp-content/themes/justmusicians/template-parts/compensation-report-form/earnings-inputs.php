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
                'tooltip'       => 'Enter the total amount of money that you, or your band, walked away with at the end of the gig after including tips and deducting any production costs you had to pay to pay the gig.',
                'placeholder'   => '0',
                'currency'      => '$',
                'required'      => true,
            ]); ?>

            <!-- Performance Duration -->
            <?php echo get_template_part('template-parts/global/form/input-icon-number', '', [
                'title'         => 'Performance Duration',
                'input_name'    => 'minutes_performed',
                'icon_filename' => 'clock.svg',
                'tooltip'       => 'This is the duration of your performance in minutes.',
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
                'tooltip'       => 'This is the number of performers in your band or your performing act, not the amount of performing acts that performed at the venue on the given night.',
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
        'tooltip'    => 'Guarantee: You were promised a specific amount to play the show<br><br>Door Deal: You were offered a percentage of ticket sales or cover charges<br><br>Bar Deal: You were promised a percentage of sales<br><br>Versus Deal: You were promised a guaranteed minimum fee or a percentage of revenue depending on which amount is higher/lower',
        'required'   => true,
    ]); ?>

    <!-- Payment Speed -->
    <?php echo get_template_part('template-parts/global/form/dropdown', '', [
        'title'      => 'When were you paid?',
        'input_name' => 'payment_speed',
        'options'    => [ 'Before the gig', 'On the day of the gig', 'Within 3 days after the gig', 'Within a week after the gig', 'Within 2 weeks after the gig', 'Within 30 days after the gig', 'Never got paid', ],
        'required'   => true,
    ]); ?>

    <!-- Payment Method -->
    <?php echo get_template_part('template-parts/global/form/dropdown', '', [
        'title'      => 'How were you paid?',
        'input_name' => 'payment_method',
        'options'    => [ 'Cash', 'Check', 'Direct Deposit', 'Zelle', 'Venmo/Cash App', 'PayPal', ],
        'required'   => true,
    ]); ?>


</section>
