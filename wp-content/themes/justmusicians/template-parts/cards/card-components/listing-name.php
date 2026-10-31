
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
        <img class="h-5 ml-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/verified.svg'; ?>" width="24" height="24" alt="Verified listing icon" />
    <?php } ?>


    <!-- Display position and rank for admins -->
    <?php if (current_user_can( 'manage_options' ) and !empty($args['rank']) and !empty($args['position']) ) { ?>
        <span class="text-12 text-grey ml-2">
            <?php echo $args['position'] . ' - ' . $args['rank']; ?>
        </span>
    <?php } ?>


</div>
