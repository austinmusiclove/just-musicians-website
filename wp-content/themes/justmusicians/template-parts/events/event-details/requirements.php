<div>

    <?php if ($args['show_heading'] == true): ?>
    <h3 class="font-bold text-16 mb-2">Applicant Requirements</h3>
    <?php endif; ?>

    <div class="flex flex-row items-center gap-1 bg-gray">

        <div class="flex items-center gap-2">
            <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/check.svg"
                x-show="requestQuote" x-cloak />
            <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/xmark.svg"
                x-show="!requestQuote" x-cloak />
            <span class="text-16">Request Quote</span>
        </div>

        <div class="flex items-center gap-2">
            <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/check.svg"
                x-show="requestDraw" x-cloak />
            <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/xmark.svg"
                x-show="!requestDraw" x-cloak />
            <span class="text-16">Request Draw</span>
        </div>

    </div>

</div>