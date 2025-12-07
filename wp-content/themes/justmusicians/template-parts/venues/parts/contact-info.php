<div class="grid grid-cols-2 gap-x-12 gap-y-4 w-full">

    <!-- Phone -->
    <?php if (!empty(get_field('phone'))) { ?>
    <div>
        <div class="flex items-center gap-1">
            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/phone.svg'; ?>" />
            <h4 class="text-16 font-semibold">Phone</h4>
        </div>
        <?php if (is_user_logged_in()) { ?>
        <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block" >
            <?php echo get_field('phone'); ?>
        </span>
        <?php } ?>
        <?php if (!is_user_logged_in()) { ?><span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis">Log in to reveal</span><?php } ?>
    </div>
    <?php } ?>

    <!-- Email -->
    <?php if (!empty(get_field('email'))) { ?>
    <div>
        <div class="flex items-center gap-1">
            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/email.svg'; ?>" />
            <h4 class="text-16 font-semibold">Email</h4>
        </div>
        <?php if (is_user_logged_in()) { ?>
        <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block">
            <?php echo get_field('email'); ?>
        </span>
        <?php } ?>
        <?php if (!is_user_logged_in()) { ?><span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis">Log in to reveal</span><?php } ?>

    </div>
    <?php } ?>

    <!-- Website -->
    <?php if (!empty(get_field('website'))) { ?>
    <div>
        <div class="flex items-center gap-1">
            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/website.svg'; ?>" />
            <h4 class="text-16 font-semibold">Website</h4>
        </div>
        <span class="text-14 text-yellow underline cursor-pointer whitespace-nowrap overflow-hidden text-ellipsis block">
            <a target="_blank"
                title="<?php echo get_field('website'); ?>"
                href="<?php echo get_field('website'); ?>"
            >
                <?php echo clean_url_for_display(get_field('website')); ?>
            </a>
        </span>
    </div>
    <?php } ?>


    <!-- No Contact Info -->
    <?php if ((empty(get_field('phone')) and empty(get_field('email')) and empty(get_field('website')))) { ?>
    <div>
        <div class="flex items-center gap-1">
            <h4 class="text-16 font-semibold">No Contact Info Available</h4>
        </div>
    </div>
    <?php } ?>
</div> <!-- End contact info -->
