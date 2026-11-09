<div class="flex items-center">


    <!-- Name -->
    <h2 class="text-22 font-bold" x-text="pName || 'Performer or Band Name'" ></h2>


    <!-- Verified badge -->
    <?php if ($args['verified']) { ?>
        <img class="h-5 ml-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/verified.svg'; ?>" width="24" height="24" alt="Verified listing icon" />
    <?php } ?>


</div>
