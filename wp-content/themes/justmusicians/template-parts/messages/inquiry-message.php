
<div x-bind:id="_getMessageElmId(message.conversation_id, message.message_id)"
    <?php if ($args['is_last']) { // infinite scroll; include this on the last result of the page ?>
    x-show="showPaginationMessages" x-cloak
    x-intersect="$nextTick(() => { if (!message.is_read) { _markAsRead(message.conversation_id, message.message_id); } _getMessages(message.conversation_id, message.message_id); })"
    <?php } else { ?>
    x-intersect="$nextTick(() => { if (!message.is_read) { _markAsRead(message.conversation_id, message.message_id); } })"
    <?php } ?>
>

    <!-- Timestamp -->
    <template x-if="message.created_at">
        <div class="text-center text-grey text-14" x-text="new Date(message.created_at.replace(' ', 'T') + 'Z').toLocaleString()"></div>
    </template>


     <!-- Message Wrapper -->
    <div class="flex items-start gap-2"
        :class="{ 'flex-row-reverse ml-auto': message.is_outgoing, 'flex-row': !message.is_outgoing }"
    >

        <!-- Profile image -->
        <img class="w-8 h-8 rounded-full mt-1" alt="Profile image" x-bind:src="message.sender_profile_image_url">

        <div>

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
                        <template x-if="(message.inquiry.date_type && message.inquiry.date_type != '') || (message.inquiry.date && message.inquiry.date != '')">
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
                        <template x-if="message.inquiry.time && message.inquiry.time != ''">
                            <div class="flex items-center gap-1">
                                <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/clock.svg'; ?>" />
                                <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="message.inquiry.time"></span>
                            </div>
                        </template>

                        <!-- Zip code -->
                        <template x-if="message.inquiry.zip_code && message.inquiry.zip_code != ''">
                            <div class="flex items-center gap-1">
                                <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
                                <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block" x-text="message.inquiry.zip_code"></span>
                            </div>
                        </template>

                    </div> <!-- End contact info -->


                    <!-- Details -->
                    <template x-if="message.inquiry.details && message.inquiry.details[0] != ''">
                        <span>

                            <div x-show="showDetails" x-cloak x-collapse x-html="message.inquiry.details"></div>

                            <div class="w-full bg-black/20 h-px my-4"></div>
                            <div class="flex flex-row justify-evenly">
                                <a class="font-bold hover:text-yellow cursor-pointer" x-show="!showDetails" x-cloak x-on:click="showDetails = true">Show Details</a>
                                <a class="font-bold hover:text-yellow cursor-pointer" x-show="showDetails" x-cloak x-on:click="showDetails = false">Show Less</a>
                                <!--<span class="border-r border-black/20"></span>
                                <a class="font-bold hover:text-yellow cursor-pointer">Decline</a>-->
                            </div>

                        </span>
                    </template>


                </div>


            </div>

        </div>

    </div>

</div>
