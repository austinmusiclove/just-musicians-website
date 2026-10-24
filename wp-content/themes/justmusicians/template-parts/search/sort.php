<div class="text-16 p-2 flex sm:items-center items-end gap-2 sm:flex-row flex-col">

    <div class="flex items-center gap-2">
        <?php echo get_template_part('template-parts/global/tooltips/tooltip', '', [
            'tooltip' => 'Learn more about the default <a class="text-yellow underline" target="_blank" href="' . site_url('/search-algorithm') . '">Hire Musicians search algorithm</a>.'
        ]); ?>

        <!-- Sort Dropdown with Alpine.js -->
        <div class="flex items-center gap-1.5 relative" x-data="{
                open: false,
                timeout: null,
                leave() { this.timeout = setTimeout(() => { this.open = false }, 100); },
                enter() { clearTimeout(this.timeout); this.open = true; }
            }">

            Sort:

            <!-- Trigger Span -->
            <span class="font-bold flex items-center cursor-pointer"
                  x-on:click="enter()"
                  x-on:click.away="leave()"
                  x-on:mouseenter="enter()"
                  x-on:mouseleave="leave()">
                Default
                <img class="mx-1.5" src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" alt="Sort Options" />
            </span>

            <!-- Dropdown Menu Box -->
            <div class="absolute top-full w-40 px-4 py-4 bg-white flex flex-col shadow-md rounded-sm z-10 right-0"
                 x-show="open" x-cloak
                 x-on:mouseenter="enter()"
                 x-on:mouseleave="leave()">
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-yellow-light/50 rounded-sm" href="#">Default</a>
                <!-- Unsupported search options
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-yellow-light/50 rounded-sm" href="#">Highest Rated</a>
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-yellow-light/50 rounded-sm" href="#">Most Reviewed</a> -->
            </div>
        </div>
    </div>

    <!-- Number of Results -->
    <?php if (!empty($args['show_number']) and $args['show_number']) { ?>
        <div class="flex items-center gap-2">
            <div class="hidden sm:block h-5 w-px bg-black/20"></div>

            <span id="max_num_results" hx-swap-oob="outerHTML"><?php

                if (!empty($args['max_num_results'])) {
                    echo $args['max_num_results'];
                    if ($args['max_num_results'] == 1) {
                        echo ' result'; }
                    else {
                        echo ' results';
                    }
                }

                if (!empty($args['location_label'])) { echo " near " . $args['location_label']; }

            ?></span>

            <div class="spinner-start htmx-indicator flex items-center justify-center">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
            </div>

        </div>
    <?php } ?>


</div>
