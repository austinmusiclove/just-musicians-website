
<?php
$element_id = 'message-' . $args['conversation_id'] . '-' . $args['message_id'];
?>

<div id="<?php echo $element_id; ?>"
    <?php if ($args['last']) { // infinite scroll; include this on the last result of the page ?>
        hx-get="<?php echo site_url('/wp-html/v1/messages/' . $args['conversation_id'] . '/?cursor=' . $args['message_id'] ); ?>"
        hx-trigger="intersect once"
        hx-swap="afterbegin"
        hx-target="#message-board"
        hx-indicator="#mb-spinner"
        hx-on::after-request="if (event.detail.successful) { dispatchEvent(new CustomEvent('scroll-to', {detail: {id: '<?php echo $element_id; ?>'}})); }"
    <?php } ?>
>

    <!-- Timestamp -->
    <div class="text-center text-grey text-14" x-text="new Date('<?php echo $args['timestamp']; ?> UTC').toLocaleString()">
        <?php echo $args['timestamp']; ?>
    </div>


    <!-- Inquiry content -->
    <div class="my-8 sidebar-module border border-black/40 rounded overflow-hidden bg-white max-w-[300px] <?php if ($args['is_outgoing']) { echo 'ml-auto'; } ?>"
        x-data="{ showDetails: false, }"
    >

        <h3 class="bg-yellow-50 font-bold py-2 px-3 cursor-pointer"><?php echo $args['inquiry']['subject'][0]; ?></h3>
        <div class="p-4 flex flex-col gap-4">
            <div class="grid gap-x-12 gap-y-4 w-full">

                <!-- Date -->
                <?php if ( !empty($args['inquiry']['date_type'][0]) or !empty($args['inquiry']['date'][0]) ) { ?>
                    <div>
                        <div class="flex items-center gap-1">
                            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/calendar.svg'; ?>" />
                            <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block">
                                <?php if (!empty($args['inquiry']['date'][0])) { echo $args['inquiry']['date'][0]; } else if ($args['inquiry']['date_type'][0] == 'Single Date') { echo 'TBD'; } else { echo $args['inquiry']['date_type'][0]; } ?>
                            </span>
                        </div>
                    </div>
                <?php } ?>

                <!-- Time -->
                <?php if (!empty($args['inquiry']['time'][0])) { ?>
                    <div>
                        <div class="flex items-center gap-1">
                            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/clock.svg'; ?>" />
                            <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block">
                                <?php echo $args['inquiry']['time'][0]; ?>
                            </span>
                        </div>
                    </div>
                <?php } ?>

                <!-- Zip code -->
                <?php if (!empty($args['inquiry']['zip_code'][0])) { ?>
                    <div>
                        <div class="flex items-center gap-1">
                            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
                            <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block">
                                <?php echo $args['inquiry']['zip_code'][0]; ?>
                            </span>
                        </div>
                    </div>
                <?php } ?>

            </div> <!-- End contact info -->


            <!-- Details -->
            <?php if (!empty($args['inquiry']['details'][0])) { ?>
                <div x-show="showDetails" x-cloak x-collapse><?php echo $args['inquiry']['details'][0]; ?> </div>
            <?php } ?>


            <div class="w-full bg-black/20 h-px"></div>
            <div class="flex flex-row justify-evenly">
                <a class="font-bold hover:text-yellow cursor-pointer" x-show="!showDetails" x-cloak x-on:click="showDetails = true">Show Details</a>
                <a class="font-bold hover:text-yellow cursor-pointer" x-show="showDetails" x-cloak x-on:click="showDetails = false">Show Less</a>
                <!--<span class="border-r border-black/20"></span>
                <a class="font-bold hover:text-yellow cursor-pointer">Decline</a>-->
            </div>


        </div>


    </div>

</div>
