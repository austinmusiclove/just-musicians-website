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
                'tooltip'       => 'Enter the total amount of money you or your band took home after the show. Include tips, and subtract any production costs you had to pay to the venue.',
                'placeholder'   => '0',
                'currency'      => '$',
                'required'      => true,
            ]); ?>

            <!-- Performance Duration -->
            <?php echo get_template_part('template-parts/global/form/input-icon-number', '', [
                'title'         => 'Performance Duration',
                'input_name'    => 'minutes_performed',
                'icon_filename' => 'clock.svg',
                'tooltip'       => 'How long did performance last in minutes?',
                'placeholder'   => '60',
                'min'           => '1',
                'unit_sufix'    => 'Minutes',
                'required'      => true,
            ]); ?>

            <!-- Number of Performers -->
            <?php echo get_template_part('template-parts/global/form/input-icon-number', '', [
                'title'         => '# of Performers',
                'input_name'    => 'total_performers',
                'icon_filename' => 'people.svg',
                'tooltip'       => 'Enter how many performers were in your band or group. (Not how many bands played on the show)',
                'placeholder'   => '1',
                'min'           => '1',
                'required'      => true,
            ]); ?>

        </div>
    </fieldgroup>

    <!-- Comp Structure -->
    <?php echo get_template_part('template-parts/global/form/dropdown', '', [
        'title'        => 'Compensation Structure',
        'input_name'   => 'comp_structure',
        'options'      => [ 'Guarantee', 'Guarantee plus Door Deal', 'Guarantee plus Bar Deal', 'Door Deal', 'Bar Deal', 'Versus Deal', 'Tips Only', 'Benefit Show', 'Unpaid', ],
        'tooltip'      => 'Guarantee: You were promised a set amount of money to play the show.<br><br>Door Deal: You were paid a share of ticket sales or cover charges.<br><br>Bar Deal: You were paid a share of bar sales.<br><br>Versus Deal: You were paid either a set amount or a share of sales, depending on which was higher or lower.',
        'other_option' => true,
        'required'     => true,
    ]); ?>

    <!-- Payment Speed -->
    <?php echo get_template_part('template-parts/global/form/dropdown', '', [
        'title'      => 'When were you paid?',
        'input_name' => 'payment_speed',
        'options'    => [ 'Before the gig', 'Same Day', 'Within 7 days', 'Within 30 days', 'Over 30 days later', 'Never got paid', ],
        'required'   => true,
    ]); ?>

    <!-- Payment Method -->
    <?php echo get_template_part('template-parts/global/form/dropdown', '', [
        'title'        => 'How were you paid?',
        'input_name'   => 'payment_method',
        'options'      => [ 'Cash', 'Check', 'ACH', 'Zelle', 'Direct Deposit', 'Venmo', 'Cash App', 'PayPal', 'Bill.com', 'Gift Card', 'None', ],
        'other_option' => true,
        'required'     => true,
    ]); ?>


</section>
