
<!-- Header -->
<header class="pt-4 md:pt-20 mb-12 mt-8 gap-2 flex flex-col">

    <!-- Back button -->
    <span class="opacity-50 cursor-pointer" x-show="inquiry != null" x-cloak>
        <a class="flex hover:underline" href="/inquiries">
            <img class="ml-[-8px] h-6 opacity-80 text-grey" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron-left.svg'; ?>" />
            <span class="text-18" >Back to inquiries</span>
        </a>
    </span>

    <!-- Title -->
    <h1 class="font-bold text-25" x-show="inquiry == null" x-cloak>All Conversations</h1>
    <span x-show="inquiry != null" x-cloak>
        <h1 class="font-bold text-25">Inquiry Responses</h1>
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
