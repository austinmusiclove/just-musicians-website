<div class="relative inline-flex" x-data="{
        open: false,
        timeout: null,
        leave() {
            this.timeout = setTimeout(() => { this.open = false }, 200);
        },
        enter() {
            clearTimeout(this.timeout);
            this.open = true;
        }
    }">

    <!-- Trigger Icon -->
    <img class="opacity-40 h-4 min-w-4 cursor-pointer hover:opacity-100" width="20" height="20"
        src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>"
        alt="Tooltip"
        x-on:click="enter()"
        x-on:click.away="leave()"
        x-on:mouseenter="enter()"
        x-on:mouseleave="leave()"
    />

    <!-- Tooltip Box -->
    <div class="z-50 absolute bottom-full left-1/2 -translate-x-1/2 mb-2"
        x-show="open" x-cloak
        x-on:mouseenter="enter()"
        x-on:mouseleave="leave()"
    >
        <div class="w-56 text-white bg-black px-4 py-3 text-14 font-normal rounded shadow-lg">
            <?php echo $args['tooltip']; ?>
        </div>
    </div>
</div>
