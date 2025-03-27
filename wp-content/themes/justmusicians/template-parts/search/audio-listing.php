<div class="py-4 relative flex flex-col sm:flex-row items-start gap-3 md:gap-7 relative">

    <div class="w-full sm:w-56 shrink-0">
        <div class="bg-yellow-light">
            <img class="w-full h-full object-cover" src="<?php echo $args['thumbnail_url']; ?>" />
        </div>
    </div>


    <div class="py-2 flex flex-col gap-y-2">

            <span class="text-14 flex items-center"><?php echo $args['date']; ?></span>
            <div class="flex flex-row">
                <h2 class="text-22 font-bold"><a href="#"><?php echo $args['name']; ?></a></h2>
                <?php if ($args['verified']) { ?>
                    <img class="h-5 ml-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/certificate-solid.svg'; ?>" />
                <?php } ?>
            </div>
            <span class="text-18"><?php echo $args['excerpt']; ?></span>
            <div class="flex items-center gap-1 flex-wrap">
                <?php foreach((array) $args['genres'] as $genre) { ?>
                <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-light hover:bg-yellow cursor-pointer inline-block"><?php echo $genre; ?></span><?php
                } ?>
            </div>
            <div class="mt-4">
                <audio controls>
                    <source src="<?php echo esc_url($args['audio']); ?>" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            </div>

    </div>

</div>
