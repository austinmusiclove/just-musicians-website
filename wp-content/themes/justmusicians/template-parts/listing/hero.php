
<?php 
$availability_html = '<div class="bg-navy text-white rounded-full font-bold py-1 px-3 uppercase text-14 w-fit">Available</div>'; 
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
            <img class="w-full h-full object-cover" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/indoor-creature.jpg' ;?>">
            <div class="<?php echo $theme['availability_wrapper_1']; ?>">
                <?php echo $availability_html; ?>
            </div>
        </div>

        <!-- Content -->
        <div class="flex flex-col gap-12 items-end <?php echo $theme['title_wrapper']; ?>">
            <div class="<?php echo $theme['availability_wrapper_2']; ?>">
                <?php echo $availability_html; ?>
            </div>
            <div class="flex flex-col gap-5 w-full">
                <div class="flex items-center gap-2">
                    <h1 class="text-32 font-bold">Indoor Creature</h1>
                    <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/verified.svg'; ?>" />
                </div>
                <p class="text-18">Indoor Creature’s music can make your mom cry. It’s happened, and they’re flattered but would also like to apologize. </p>
                <div class="flex gap-2 items-center">
                    <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
                    <span>Austin, TX</span>
                </div>
                <div class="flex items-center gap-1">
                    <?php $tag_classes = 'bg-yellow-light cursor-pointer hover:bg-yellow px-2 py-0.5 rounded-full font-bold text-12'; ?>
                    <span class="<?php echo $tag_classes;?>">pop</span>
                    <span class="<?php echo $tag_classes;?>">rock</span>
                    <span class="<?php echo $tag_classes;?>">soul</span>
                </div>
            </div>
        </div>

    </div>

</section>