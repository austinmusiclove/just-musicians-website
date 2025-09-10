
<!-- Header -->
<header class="pt-4 md:pt-20 mb-4 mt-8 gap-2 flex flex-col">

    <!-- Back button -->
    <span class="opacity-50 cursor-pointer" x-show="inquiry != null" x-cloak>
        <a class="flex items-center hover:underline" href="<?php echo site_url('/messages/'); ?>">
            <svg width="16" height="16" class="icon_svg"><path d="M12.75 7.25H5.038l1.747-1.78a.75.75 0 0 0-1.07-1.05l-3 3.055a.75.75 0 0 0 0 1.05l3 3.055a.75.75 0 0 0 1.07-1.05L5.038 8.75h7.712a.75.75 0 1 0 0-1.5Z"></path></svg>
            <span class="ml-2 text-18">All Messages</span>
        </a>
    </span>

    <!-- Title -->
    <h1 class="font-bold text-25" x-show="inquiry == null" x-cloak>All Messages</h1>
    <span class="flex flex-col gap-1 my-4" x-show="inquiry != null" x-cloak>
        <h1 class="text-16 opacity-80 text-grey">Responses For:</h1>
        <h2 class="font-bold text-25"><?php echo $args['inquiry_data'] ? $args['inquiry_data']['subject'] : ''; ?></h2>
    </span>

</header>

<div class="flex-1 flex flex-col h-full overflow-hidden" x-data="{
    showConversations: true,
    showInquiryDetails: false,
    hideTabs() {
        this.showConversations = false;
        this.showInquiryDetails = false;
    },
}">

    <!-- Tabs -->
    <div class="flex items-start justify-between border-b border-black/20" x-show="inquiry != null" x-cloak>
        <div class="flex gap-6 items-start">
            <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showConversations}" x-on:click="hideTabs(); showConversations = true;">Messages</div>
            <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showInquiryDetails}" x-on:click="hideTabs(); showInquiryDetails = true;">Inquiry Details</div>
        </div>
    </div>


    <!-- Conversations -->
    <div class="flex-1 flex flex-col overflow-hidden pt-4" x-show="showConversations" x-cloak>


        <!-- Show Conversations -->
        <?php echo get_template_part('template-parts/messages/conversations-display', '', [ 'inquiry_id' => $args['inquiry_id'] ]); ?>

        <!-- Spinner -->
        <div class="flex items-center justify-center" x-show="getCvInFlight" x-cloak>
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
        </div>

    </div>

    <!-- Inquiry Details -->
    <div class="flex-1 flex overflow-hidden" x-show="showInquiryDetails" x-cloak >

        <!-- Inquiry details -->
        <?php echo get_template_part('template-parts/messages/inquiry-detail', '', []); ?>

    </div>

</div>
