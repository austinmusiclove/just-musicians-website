
<template x-if="inquiry">

    <div class="flex-1 overflow-y-auto my-8 pr-8"
        x-data="{
            _showDate(inquiry)   { return showDate(inquiry); },
            _showTime(inquiry)   { return showTime(inquiry); },
            _showBudget(inquiry) { return showBudget(inquiry); },
        }"
    >

        <span x-show="!editInquiryMode" x-cloak>
            <?php echo get_template_part('template-parts/messages/parts/inquiry-detail/inquiry-info-display', '', [] ); ?>
        </span>

        <!-- Inquiry edit form -->
        <span x-show="editInquiryMode" x-cloak>
            <?php echo get_template_part('template-parts/messages/parts/inquiry-detail/inquiry-edit-form', '', [] ); ?>
        </span>

    </div>

</template>
