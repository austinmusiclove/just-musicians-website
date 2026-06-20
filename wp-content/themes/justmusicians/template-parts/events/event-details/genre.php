<div class="flex items-start gap-1">
    <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/music.svg" />
    <?php if (!empty($args['genres'])) { ?>
        <div class="flex flex-wrap items-center gap-1">
            <?php foreach ($args['genres'] as $genre) { ?>
                <span class="bg-yellow-light px-2 py-0.5 rounded-full font-bold text-12"><?php echo esc_html($genre); ?></span>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p class="text-16 text-black/50">No genres specified</p>
    <?php } ?>
</div>
