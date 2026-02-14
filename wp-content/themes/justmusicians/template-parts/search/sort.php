<div class="text-16 py-2 flex items-center gap-2">

    <!-- Sort -->
    <div class="flex items-center gap-2 ">
        <?php echo get_template_part('template-parts/global/tooltip', '', [
            'tooltip' => 'Learn more about the default Hire More Musicians search algorithm <a class="text-yellow underline" target="_blank" href="' . site_url('/search-algorithm') . '">here</a>.'
        ]); ?>
        <div class="flex items-center gap-1.5 group relative">
            Sort:
            <span class="font-bold flex items-center">
                Default
                <img class="ml-1.5" src="<?php echo get_template_directory_uri() . '/lib/images/icons/caret-down.svg'; ?>" />
            </span>
            <div class="absolute top-full w-40 px-4 py-4 bg-white hidden group-hover:flex flex-col shadow-md rounded-sm z-10 right-0">
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-yellow-light/50 rounded-sm" href="#">
                    Default
                </a>
                <!-- Unsupported search options
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-yellow-light/50 rounded-sm" href="#">
                    Highest Rated
                </a>
                <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-yellow-light/50 rounded-sm" href="#">
                    Most Reviewed
                </a>
                -->
            </div>
        </div>
    </div>

    <!-- Number of Results -->
    <?php if (!empty($args['show_number']) and $args['show_number']) { ?>
        <div class="flex items-center gap-2">
            <div class="h-5 w-px bg-black/20"></div>

            <span id="max_num_results" hx-swap-oob="outerHTML"><?php

                if (!empty($args['max_num_results'])) {
                    echo $args['max_num_results'];
                    if ($args['max_num_results'] == 1) {
                        echo ' result'; }
                    else {
                        echo ' results';
                    }
                }

            ?></span>

        </div>
    <?php } ?>

    <div class="spinner-start htmx-indicator my-8 flex items-center justify-center">
        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
    </div>

</div>
