<div data-popup="filter" class="popup-wrapper w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center" x-show="<?php echo $args['x-show']; ?>" x-transition x-cloak>
    <div data-trigger="filter" class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer" x-on:click="<?php echo $args['x-show']; ?> = ! <?php echo $args['x-show']; ?>" ></div>

    <div class="bg-white relative w-full h-full sm:h-auto sm:w-auto pt-20 px-4 sm:px-8 md:px-20 pb-32" style="max-width: 780px;">

        <img data-trigger="filter" class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" x-on:click="<?php echo $args['x-show']; ?> = ! <?php echo $args['x-show']; ?>" />

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-y-4 gap-x-10 custom-checkbox">
        <?php
            foreach((array) $args['labels'] as $label) {
                echo get_template_part('template-parts/filters/elements/checkbox', '', [
                    'label' => $label,
                    'input_id' => strtolower($args['name']) . preg_replace("/[^A-Za-z0-9]/", '', $label) . 'Checkbox', // used in nave and search options
                    'value' => $label,
                    'name' => $args['name'],
                    'x-model' => $args['name'] . 'Checkboxes',
                    'x-ref' => strtolower($args['name']) . preg_replace("/[^A-Za-z0-9]/", '', $label), // same formula used in tags.php
                    'is_array' => true,
                    'checked' => false,
                ]);
            }
        ?>
        </div>

        <button type="button" class="bg-navy absolute bottom-10 right-10 shadow-black-offset border-2 border-black hover:bg-yellow hover:text-black text-white font-sun-motter text-16 px-5 py-3" x-on:click="<?php echo $args['x-show']; ?> = ! <?php echo $args['x-show']; ?>"> Update</button>

    </div>
</div>
