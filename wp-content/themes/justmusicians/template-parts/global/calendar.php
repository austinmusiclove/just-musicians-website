<div class="w-full sm:w-1/2 <?php echo $args['responsive']; ?>">

<?php if ($args['order'] == 2) { 
    $arrow_right_class = 'block';
    } else {
        $arrow_right_class = 'block sm:hidden';
    }
?>    



    <div class="border-b border-black/20 text-center pb-2 mb-4 block w-full relative">
        <?php if ($args['order'] == 1) { ?>
            <img class="h-6 absolute bottom-2 left-0 z-0 cursor-pointer rotate-90" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron.svg'; ?>" />
        <?php } ?>
        <?php echo $args['month']; ?>
            <img class="<?php echo $arrow_right_class; ?> h-6 absolute bottom-2 right-0 z-0 cursor-pointer -rotate-90" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron.svg'; ?>" />
    </div>
    <div class="w-full">
        <?php echo generate_calendar_grid($args['month'], $args['year'], $args['event_day'], $args['instance']); ?>
    </div>
</div>