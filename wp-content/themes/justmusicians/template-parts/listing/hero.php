
<?php
if ($args['instance'] == 'listing-form') {
    $theme = [
        'wrapper_class' => 'py-4',
        'container_class' => '',
        'title_wrapper' => 'py-4',
        'availability_wrapper_1' => 'block absolute sm:top-4 sm:right-4',
        'availability_wrapper_2' => 'hidden'
    ];
} else {
    $theme = [
        'wrapper_class' => 'my-4 lg:my-16',
        'container_class' => 'container grid lg:grid-cols-2',
        'title_wrapper' => 'lg:px-16 py-4 lg:py-10',
        'availability_wrapper_1' => 'block lg:hidden absolute top-2 right-2 sm:top-4 sm:right-4',
        'availability_wrapper_2' => 'hidden lg:block'
    ];
}
?>

<section class="<?php echo $theme['wrapper_class']; ?>">

    <div class="<?php echo $theme['container_class']; ?>">
        <div class="bg-yellow w-full aspect-4/3 shadow-black-offset border-4 border-black relative">
            <img class="w-full h-full object-cover" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" />
            <div class="<?php echo $theme['availability_wrapper_1']; ?>">
                <!--<div class="bg-navy text-white rounded-full font-bold py-1 px-3 uppercase text-14 w-fit">Available</div>-->
            </div>
        </div>

        <!-- Content -->
        <div class="flex flex-col gap-12 items-end <?php echo $theme['title_wrapper']; ?>">
            <div class="<?php echo $theme['availability_wrapper_2']; ?>">
                <!--<div class="bg-navy text-white rounded-full font-bold py-1 px-3 uppercase text-14 w-fit">Available</div>-->
            </div>
            <div class="flex flex-col gap-5 w-full">
                <div class="flex items-center gap-2">
                    <h1 class="text-32 font-bold"
                        <?php if (!empty($args['alpine_name'])) { echo 'x-text="' . $args['alpine_name'] . ' === \'\' ? \'' . $args['name'] . '\' : ' . $args['alpine_name'] . '"'; } ?>
                    >
                        <?php echo get_field('name'); ?>
                    </h1>
                    <?php if (get_field('verified') === true) { ?>
                        <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/verified.svg'; ?>" />
                    <?php } ?>
                </div>
                <p class="text-18" <?php if (!empty($args['alpine_description'])) { echo 'x-text="' . $args['alpine_description'] . ' === \'\' ? \'' . $args['description'] . '\' : ' . $args['alpine_description'] . '"'; } ?>><?php echo get_field('description'); ?></p>
                <div class="flex gap-2 items-center">
                    <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
                    <span <?php if (!empty($args['alpine_location'])) { echo 'x-text="' . $args['alpine_location'] . ' === \'\' ? \'' . $args['location'] . '\' : ' . $args['alpine_location'] . '"'; } ?>><?php echo get_field('city') . ', ' . get_field('state'); ?></span>
                </div>
                <div class="flex flex-wrap items-center gap-1">
                    <?php
                    if (!empty($args['genres']) && !is_wp_error($args['genres'])) {
                        foreach ($args['genres'] as $genre) { ?>
                            <span class="bg-yellow-light cursor-pointer hover:bg-yellow px-2 py-0.5 rounded-full font-bold text-12"><?php echo $genre->name; ?></span>
                        <?php }
                    } ?>
                </div>
                <?php if (!empty(get_field('ensemble_size')) and is_array(get_field('ensemble_size'))) { ?>
                <div>
                    <div class="flex items-center gap-1">
                        <img style="height: .9rem" src="<?php echo get_template_directory_uri() . '/lib/images/icons/people.svg'; ?>" />
                        <h4 class="text-16 font-semibold">Ensemble size</h4>
                    </div>
                    <span class="text-14 v"><?php echo implode(', ', get_field('ensemble_size')); ?></span>
                </div>
                <?php } ?>
            </div>
        </div>

    </div>

</section>
