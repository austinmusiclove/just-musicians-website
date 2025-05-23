<section class="w-full"> <!-- Start calendar -->
    <div class="flex items-center gap-2 mb-5">
        <img class="h-8" src="<?php echo get_template_directory_uri() . '/lib/images/icons/calendar.svg'; ?>" />
        <h2 class="text-25 font-bold">Calendar</h2>
    </div>
    <div class="border border-black/40 rounded w-full"> 
        <div class="flex justify-between items-center border-b border-black/40 pl-4 pr-2">
            <div class="pt-3 sm:pt-4 flex gap-4 sm:gap-6 items-start">
                <?php 
                    $button_class = 'calendar-tab pb-2 flex items-center gap-2 text-14 sm:text-16'; 
                    $dot_class = 'h-2 w-2 sm:h-2.5 sm:w-2.5 rounded-full mx-1 md:mx-1.5';
                ?>
                <button class="<?php echo $button_class; ?> active">
                    Add availablity
                    <div class="<?php echo $dot_class; ?>" style="background-color: #F4E5CB"></div>
                </button>
                <button class="<?php echo $button_class; ?>">
                    Add gig
                    <div class="<?php echo $dot_class; ?>" style="background-color: #D29429"></div>
                </button>
                <button class="<?php echo $button_class; ?>">
                    Add unavailablity
                    <div class="<?php echo $dot_class; ?>" style="background-color: #CCCCCC"></div>
                </button>
            </div>
            <button class="hidden sm:block px-2.5 py-2 border border-black/20 rounded font-bold text-14">Today</button>
        </div>
        <div class="flex flex-row p-8 gap-8">
            <?php echo get_template_part('template-parts/global/calendar', '', array(
                'month' => 'April',
                'year' => '2025',
                'order' => 1,
                'responsive' => '',
                'event_day' => null
            )); ?>
            <?php echo get_template_part('template-parts/global/calendar', '', array(
                'month' => 'May',
                'year' => '2025',
                'order' => 2,
                'responsive' => 'hidden sm:block',
                'event_day' => null
            )); ?>
        </div>
    </div>
</section> 