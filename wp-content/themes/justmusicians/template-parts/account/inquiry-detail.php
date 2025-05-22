
<div class="my-4 p-4 relative flex flex-row items-start gap-3 md:gap-6 relative border border-yellow rounded-md">

    <div class="py-2 flex flex-col gap-y-2">

        <!-- When -->
        <?php if ( !empty(get_field('date_type', $args['post_id'])) or !empty(get_field('date', $args['post_id'])) or !empty(get_field('time', $args['post_id'])) or !empty(get_field('date_time_details', $args['post_id']))) { ?>
            <div class="font-semibold mt-3">When</div>
        <?php } ?>
        <?php if (!empty(get_field('date_type', $args['post_id']))) { ?>
            <div><span class="px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"><?php echo get_field('date_type', $args['post_id']); ?></span></div>
        <?php } ?>
        <?php if (!empty(get_field('date', $args['post_id']))) { ?>
            <div><span class="px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"><?php echo get_field('date', $args['post_id']); ?></span></div>
        <?php } ?>
        <?php if (!empty(get_field('time', $args['post_id']))) { ?>
            <div><span class="px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"><?php echo get_field('time', $args['post_id']); ?></span></div>
        <?php } ?>
        <?php if (!empty(get_field('date_time_details', $args['post_id']))) { ?>
            <div><?php echo get_field('date_time_details', $args['post_id']); ?></div>
        <?php } ?>


        <!-- Where -->
        <?php if (!empty(get_field('zip_code', $args['post_id'])) or !empty(get_field('location_details', $args['post_id']))) { ?>
            <div class="font-semibold mt-3">Where</div>
        <?php } ?>
        <?php if (!empty(get_field('zip_code', $args['post_id']))) { ?>
            <div>
                <span>Zip Code: </span>
                <span class="px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"><?php echo get_field('zip_code', $args['post_id']); ?></span>
            </div>
        <?php } ?>
        <?php if (!empty(get_field('location_details', $args['post_id']))) { ?>
            <div><?php echo get_field('location_details', $args['post_id']); ?></div>
        <?php } ?>


        <!-- Performance -->
        <?php if (!empty(get_field('duration', $args['post_id'])) or !empty(get_field('ensemble_size', $args['post_id'])) or !empty(get_field('genres', $args['post_id']))) { ?>
            <div class="font-semibold mt-3">Performance Specifications</div>
        <?php } ?>
        <?php if (!empty(get_field('duration', $args['post_id']))) { ?>
            <div>
                <span>Performance Duration: </span>
                <span class="px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"> <?php echo get_field('duration', $args['post_id']); ?> Hours</span>
            </div>
        <?php } ?>
        <?php if (!empty(get_field('ensemble_size', $args['post_id']))) { ?>
            <div>
                <span>Desired Ensemble Sizes: </span>
                <?php foreach(get_field('ensemble_size', $args['post_id']) as $option) { ?>
                    <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"> <?php echo $option; ?> </span>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if (!empty(get_field('genres', $args['post_id']))) { ?>
            <div>
                <span>Desired Genres: </span>
                <?php foreach(get_field('genres', $args['post_id']) as $option) { ?>
                    <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"> <?php echo $option; ?> </span>
                <?php } ?>
            </div>
        <?php } ?>


        <!-- Equipment -->
        <?php if (!empty(get_field('equipment_requirement', $args['post_id'])) or !empty(get_field('equipment_details', $args['post_id']))) { ?>
            <div class="font-semibold mt-3">Equipment</div>
        <?php } ?>
        <?php if (!empty(get_field('equipment_requirement', $args['post_id']))) { ?>
            <div><?php echo get_field('equipment_requirement', $args['post_id']); ?></div>
        <?php } ?>
        <?php if (!empty(get_field('equipment_details', $args['post_id']))) { ?>
            <div><?php echo get_field('equipment_details', $args['post_id']); ?></div>
        <?php } ?>


        <!-- Details -->
        <?php if (!empty(get_field('details', $args['post_id']))) { ?>
            <div class="font-semibold mt-3">Details</div>
        <?php } ?>
        <?php if (!empty(get_field('details', $args['post_id']))) { ?>
            <div><?php echo get_field('details', $args['post_id']); ?> </div>
        <?php } ?>
    </div>

</div>
