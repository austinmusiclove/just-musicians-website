
<!-- Name and verification badge -->
<div class="flex items-center">


    <!-- Name -->
    <h2 class="text-22 font-bold">
        <a href="<?php if (!$args['is_preview']) { echo $args['permalink']; } ?>"
            <?php if ($args['is_preview'])  { ?> x-text="pName || 'Performer or Band Name'" <?php } ?>
            <?php if (!$args['is_preview']) { ?> target="_blank" <?php } ?>>
            <?php if (!$args['is_preview']) { echo $args['name']; } ?>
        </a>
    </h2>


    <!-- Verified badge -->
    <?php if ($args['verified']) { ?>
        <img class="h-5 ml-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/verified.svg'; ?>" />
    <?php } ?>


</div>
