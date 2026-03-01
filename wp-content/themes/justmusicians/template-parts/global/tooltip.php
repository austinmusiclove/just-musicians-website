<div class="relative inline-flex group">
    <img
        class="opacity-40 h-4 cursor-pointer hover:opacity-100"
        src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>"
    />

    <div class="z-50 absolute bottom-full left-1/2 -translate-x-1/2 hidden group-hover:block hover:block">
        <div class="mb-2 w-56 text-white bg-black px-4 py-3 text-14 font-normal rounded">
            <?php echo $args['tooltip']; ?>
        </div>
    </div>
</div>

