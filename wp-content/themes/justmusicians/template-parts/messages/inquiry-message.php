
<div x-bind:id="_getMessageElmId(message.conversation_id, message.message_id)"
    <?php if ($args['is_last']) { // infinite scroll; include this on the last result of the page ?>
    x-show="showPaginationMessages" x-cloak
    x-intersect.once="console.log('intersect inquiry'); $nextTick(() => { _getMessages(message.conversation_id, message.message_id); })"
    <?php } ?>
>

    <!-- Timestamp -->
    <template x-if="message.created_at">
        <div class="text-center text-grey text-14" x-text="new Date(message.created_at + ' UTC').toLocaleString()"></div>
    </template>


    <!-- Sender name -->
    <div class="text-14"
        :class="{ 'text-right': message.is_outgoing, 'text-left': !message.is_outgoing }"
        x-text="message.sender_name"
    ></div>

    <!-- Inquiry content -->
    <div class="mb-8 sidebar-module border border-black/40 rounded overflow-hidden bg-white max-w-[300px]"
        :class="{ 'ml-auto': message.is_outgoing }"
        x-data="{ showDetails: false, }"
    >

        <h3 class="bg-yellow-50 font-bold py-2 px-3 cursor-pointer" x-html="message.inquiry.subject"></h3>
        <div class="p-4 flex flex-col gap-4">
            <div class="grid gap-x-12 gap-y-4 w-full">

                <!-- Date -->
                <template x-if="message.inquiry.date_type != '' || message.inquiry.date != ''">
                    <div class="flex items-center gap-1">
                        <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/calendar.svg'; ?>" />
                        <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block"
                            x-data="{
                                showDate(inquiry) {
                                    if (inquiry.date != '') {
                                        return inquiry.date;
                                    } else {
                                        return inquiry.date_type;
                                    }
                                },
                            }"
                            x-text="showDate(message.inquiry)"
                        ></span>
                    </div>
                </template>

                <!-- Time -->
                <template x-if="message.inquiry.time != ''">
                    <div class="flex items-center gap-1">
                        <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/clock.svg'; ?>" />
                        <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="message.inquiry.time"></span>
                    </div>
                </template>

                <!-- Zip code -->
                <template x-if="message.inquiry.zip_code != ''">
                    <div class="flex items-center gap-1">
                        <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
                        <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="message.inquiry.zip_code"></span>
                    </div>
                </template>

            </div> <!-- End contact info -->


            <!-- Details -->
            <template x-if="message.inquiry.details != ''">
                <div x-show="showDetails" x-cloak x-collapse x-html="message.inquiry.details"></div>
            </template>


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
