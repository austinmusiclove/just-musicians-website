<div x-data="{ showSidebar: <?php if ($args['collapsible']) { echo 'false'; } else { echo 'true'; } ?> }">
    <div class="flex items-center gap-8 mb-4">
        <h2 class="font-bold text-20" x-show="showSidebar" x-transition x-cloak>My Account</h2>
        <?php if ($args['collapsible']) { ?>
            <button class="w-4 flex items-center justify-center" x-on:click="showSidebar = !showSidebar">
                <img class="rotate-90 h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
            </button>
        <?php } ?>
    </div>

    <?php echo get_template_part('template-parts/menus/account-sidebar-nav', '', $args); ?>
</div>
