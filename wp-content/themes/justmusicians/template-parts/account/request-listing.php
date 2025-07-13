
<div class="my-8 sidebar-module border border-black/40 rounded overflow-hidden bg-white"
    <?php if ($args['last'] and !$args['is_last_page']) { // infinite scroll; include this on the last result of the page as long as it is not the final page ?>
    hx-get="/wp-html/v1/requests/?page=<?php echo $args['next_page']; ?>"
    hx-trigger="revealed once"
    hx-swap="beforeend"
    hx-target="#results"
    hx-indicator="#spinner"
    <?php } ?>
>

    <h3 class="bg-yellow-50 font-bold py-2 px-3 cursor-pointer"><?php echo $args['subject']; ?></h3>
    <div class="p-4 flex flex-col gap-4">

        <div class="grid gap-x-12 gap-y-4 w-full">
            <!-- Date -->
            <?php if ( !empty($args['date_type']) or !empty($args['date']) ) { ?>
                <div>
                    <div class="flex items-center gap-1">
                        <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/calendar.svg'; ?>" />
                        <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block">
                            <?php if (!empty($args['date'])) { echo $args['date']; } else if ($args['date_type'] == 'Single Date') { echo 'TBD'; } else { echo $args['date_type']; } ?>
                        </span>
                    </div>
                </div>
            <?php } ?>
            <!-- Time -->
            <?php if (!empty($args['time'])) { ?>
                <div>
                    <div class="flex items-center gap-1">
                        <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/clock.svg'; ?>" />
                        <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block">
                            <?php echo $args['time']; ?>
                        </span>
                    </div>
                </div>
            <?php } ?>
            <!-- Zip code -->
            <?php if (!empty($args['zip_code'])) { ?>
                <div>
                    <div class="flex items-center gap-1">
                        <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
                        <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block">
                            <?php echo $args['zip_code']; ?>
                        </span>
                    </div>
                </div>
            <?php } ?>

        </div> <!-- End contact info -->


        <!-- Details -->
        <?php if (!empty($args['details'])) { ?>
            <div><?php echo $args['details']; ?> </div>
        <?php } ?>


        <div class="w-full bg-black/20 h-px"></div>
        <div class="flex flex-row justify-evenly">
            <a class="font-bold hover:text-yellow cursor-pointer">Respond</a>
            <span class="border-r border-black/20"></span>
            <a class="font-bold hover:text-yellow cursor-pointer">Decline</a>
        </div>


    </div>


</div>
