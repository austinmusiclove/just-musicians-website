<div>
    <h3 class="font-bold text-16 mb-2">Applicant Requirements</h3>
    <div class="flex flex-col gap-1">
        <div class="flex items-center gap-2">
            <?php if ($args['request_quote']) { ?>
                <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/check.svg" />
            <?php } else { ?>
                <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/xmark.svg" />
            <?php } ?>
            <span class="text-16">Request Quote</span>
        </div>
        <div class="flex items-center gap-2">
            <?php if ($args['request_draw']) { ?>
                <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/check.svg" />
            <?php } else { ?>
                <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/xmark.svg" />
            <?php } ?>
            <span class="text-16">Request Draw</span>
        </div>
    </div>
</div>
