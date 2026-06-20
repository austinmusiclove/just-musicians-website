<div>

    <h3 class="font-bold text-16 mb-2">Applicant Requirements</h3>

    <div class="flex flex-col gap-1">

        <div class="flex items-center gap-2">
            <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/check.svg" x-show="request_quote" x-cloak />
            <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/xmark.svg" x-show="!request_quote" x-cloak />
            <span class="text-16">Request Quote</span>
        </div>

        <div class="flex items-center gap-2">
            <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/check.svg" x-show="request_draw" x-cloak />
            <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/xmark.svg" x-show="!request_draw" x-cloak />
            <span class="text-16">Request Draw</span>
        </div>

    </div>

</div>
