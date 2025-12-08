<?php if (!empty(get_field('description'))) { ?>

<div>

    <h2 class="text-25 font-bold mb-5">About</h2>
    <p class="mb-4"><?php echo nl2br(get_field('description')); ?></p>

</div>

<?php } ?>
