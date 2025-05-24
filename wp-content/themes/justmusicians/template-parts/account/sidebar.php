<script>
document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('collapse');
    const sidebar = document.querySelector('.sidebar');

    button.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
    });
});
</script>

<div>
    <div class="flex items-center gap-8 mb-4">
        <h2 class="font-bold text-20 hide-if-collapsed">My Account</h2>
        <?php if ($args['collapsible']) { ?>
            <button id="collapse" class="w-4 flex items-center justify-center">
                <img class="rotate-90 h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
            </button>
        <?php } ?>
    </div>

    <div class="bg-white font-regular font-sans text-16 flex flex-col -ml-2">
<!--
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="#">
            <img class="w-4" src="<?php //echo get_template_directory_uri() . '/lib/images/icons/user-solid.svg'; ?>" />
            Profile
        </a>
-->
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="/collections">
            <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/album-collection-solid.svg'; ?>" />
            <span class="inline-block pr-6 hide-if-collapsed">Collections</span>
        </a>
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="/listings">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/list-solid.svg'; ?>" />
            <span class="inline-block pr-6 hide-if-collapsed">My Listings</span>
        </a>
        <a class="px-2 py-1.5 flex items-center gap-2 rounded-sm hover:bg-yellow-light/50 opacity-80 hover:opacity-100" href="<?php echo wp_logout_url('/'); ?>">
            <img class="w-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/log-out.svg'; ?>" />
            <span class="inline-block pr-6 hide-if-collapsed">Log Out</span>
        </a>
    </div>
</div>
