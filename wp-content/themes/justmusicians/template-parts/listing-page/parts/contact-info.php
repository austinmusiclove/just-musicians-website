
<div class="grid grid-cols-2 gap-x-12 gap-y-4 w-full">

    <!-- Phone -->
    <?php if (!empty(get_field('phone')) or $args['is_preview']) { ?>
    <div <?php if ($args['is_preview']) { ?>x-show="pPhone" x-cloak <?php } ?>>
        <div class="flex items-center gap-1">
            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/phone.svg'; ?>" />
            <h4 class="text-16 font-semibold">Phone</h4>
        </div>
        <?php if (is_user_logged_in()) { ?>
        <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block" <?php if ($args['is_preview']) { ?>x-text="pPhone"<?php } ?>>
            <?php if (!$args['is_preview']) { echo get_field('phone'); } ?>
        </span>
        <?php } ?>
        <?php if (!is_user_logged_in()) { ?><span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis">Log in to reveal</span><?php } ?>
    </div>
    <?php } ?>

    <!-- Email -->
    <?php if (!empty(get_field('email')) or $args['is_preview']) { ?>
    <div <?php if ($args['is_preview']) { ?>x-show="pEmail" x-cloak <?php } ?>>
        <div class="flex items-center gap-1">
            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/email.svg'; ?>" />
            <h4 class="text-16 font-semibold">Email</h4>
        </div>
        <?php if (is_user_logged_in()) { ?>
        <span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis block" <?php if ($args['is_preview']) { ?>x-text="pEmail"<?php } ?>>
            <?php if (!$args['is_preview']) { echo get_field('email'); } ?>
        </span>
        <?php } ?>
        <?php if (!is_user_logged_in()) { ?><span class="text-14 whitespace-nowrap overflow-hidden text-ellipsis">Log in to reveal</span><?php } ?>

    </div>
    <?php } ?>

    <!-- Website -->
    <?php if (!empty(get_field('website')) or $args['is_preview']) { ?>
    <div <?php if ($args['is_preview']) { ?>x-show="pWebsite" x-cloak <?php } ?>>
        <div class="flex items-center gap-1">
            <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/website.svg'; ?>" />
            <h4 class="text-16 font-semibold">Website</h4>
        </div>
        <span class="text-14 text-yellow underline cursor-pointer whitespace-nowrap overflow-hidden text-ellipsis block">
            <a href="<?php echo get_field('website'); ?>" title="<?php echo get_field('website'); ?>" target="_blank" <?php if ($args['is_preview']) { ?>x-text="pWebsite"<?php } ?>>
                <?php if (!$args['is_preview']) { echo clean_url_for_display(get_field('website')); } ?>
            </a>
        </span>
    </div>
    <?php } ?>


    <!-- No Contact Info -->
    <?php if ((empty(get_field('phone')) and empty(get_field('email')) and empty(get_field('website'))) or $args['is_preview']) { ?>
    <div <?php if ($args['is_preview']) { ?>x-show="!pPhone & !pEmail & !pWebsite" x-cloak <?php } ?>>
        <div class="flex items-center gap-1">
            <h4 class="text-16 font-semibold">No Contact Info Available</h4>
        </div>
    </div>
    <?php } ?>
</div> <!-- End contact info -->
