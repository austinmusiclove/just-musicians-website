<div data-popup="filter" class="popup-wrapper w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center" x-show="<?php echo $args['x-show']; ?>" x-transition x-cloak>
    <div data-trigger="filter" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"
        x-on:click="<?php echo $args['x-show']; ?> = ! <?php echo $args['x-show']; ?>; tagModalSearchQuery = '';"
    ></div>

    <div class="bg-white relative w-full h-full pt-20 px-4 sm:px-8 md:px-20 pb-20 md:max-h-[550px] max-w-[780px]">
        <img data-trigger="filter" class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"
            x-on:click="<?php echo $args['x-show']; ?> = ! <?php echo $args['x-show']; ?>; tagModalSearchQuery = '';"
        />

        <h3 class="font-bold text-18 mb-6"><?php echo $args['title']; ?></h3>
        <?php if ($args['has_search_bar']) { ?>
        <input type='text' name='taxonomy-search' class="mb-6" placeholder="search..." <?php if ($args['has_search_bar']) { ?> x-model="tagModalSearchQuery" <?php } ?>></input>
        <?php } ?>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-y-4 gap-x-10 custom-checkbox overflow-scroll max-h-[500px] md:max-h-[240px]">
        <?php
            foreach((array) $args['labels'] as $label) {
                echo get_template_part('template-parts/filters/elements/checkbox', '', [
                    'label' => $label,
                    'value' => $label,
                    'name' => $args['name'],
                    'x-model' => $args['x-model'],
                    'x-ref' => get_checkbox_ref_string($args['name'], $label),
                    'x-show' => $args['has_search_bar'] ? "showTagModalOption('" . str_replace(["'", '"'], '', $label) . "')" : '',
                    'is_array' => true,
                    'checked' => false,
                ]);
            }
        ?>
        </div>

        <button type="button" class="bg-navy float-right shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-5 py-3 mt-8"
            x-on:click="<?php echo $args['x-show']; ?> = ! <?php echo $args['x-show']; ?>; tagModalSearchQuery = '';">
         Update
        </button>

    </div>
</div>
