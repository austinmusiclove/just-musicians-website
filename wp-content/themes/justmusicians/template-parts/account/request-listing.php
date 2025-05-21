
<div class="my-4 p-4 relative flex flex-row items-start gap-3 md:gap-6 relative border border-yellow rounded-md"
    <?php if ($args['last'] and !$args['is_last_page']) { // infinite scroll; include this on the last result of the page as long as it is not the final page ?>
    hx-get="/wp-html/v1/requests/?page=<?php echo $args['next_page']; ?>"
    hx-trigger="revealed once"
    hx-swap="beforeend"
    hx-target="#results"
    hx-indicator="#spinner"
    <?php } ?>
>

    <div class="py-2 flex flex-col gap-y-2">

        <!-- Subject -->
        <div><h2 class="text-20 sm:text-20 font-semibold cursor-pointer"><?php echo $args['subject']; ?></h2></div>


        <!-- When -->
        <?php if ( !empty($args['date_type']) or !empty($args['date']) or !empty($args['time']) or !empty($args['date_time_details'])) { ?>
            <div class="font-semibold mt-3">When</div>
        <?php } ?>
        <?php if (!empty($args['date_type'])) { ?>
            <div><span class="px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"><?php echo $args['date_type']; ?></span></div>
        <?php } ?>
        <?php if (!empty($args['date'])) { ?>
            <div><span class="px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"><?php echo $args['date']; ?></span></div>
        <?php } ?>
        <?php if (!empty($args['time'])) { ?>
            <div><span class="px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"><?php echo $args['time']; ?></span></div>
        <?php } ?>
        <?php if (!empty($args['date_time_details'])) { ?>
            <div><?php echo $args['date_time_details']; ?></div>
        <?php } ?>


        <!-- Where -->
        <?php if (!empty($args['zip_code']) or !empty($args['location_details'])) { ?>
            <div class="font-semibold mt-3">Where</div>
        <?php } ?>
        <?php if (!empty($args['zip_code'])) { ?>
            <div>
                <span>Zip Code: </span>
                <span class="px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"><?php echo $args['zip_code']; ?></span>
            </div>
        <?php } ?>
        <?php if (!empty($args['location_details'])) { ?>
            <div><?php echo $args['location_details']; ?></div>
        <?php } ?>


        <!-- Performance -->
        <?php if (!empty($args['duration']) or !empty($args['ensemble_size']) or !empty($args['genres'])) { ?>
            <div class="font-semibold mt-3">Performance Specifications</div>
        <?php } ?>
        <?php if (!empty($args['duration'])) { ?>
            <div>
                <span>Performance Duration: </span>
                <span class="px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"> <?php echo $args['duration']; ?> Hours</span>
            </div>
        <?php } ?>
        <?php if (!empty($args['ensemble_size'])) { ?>
            <div>
                <span>Desired Ensemble Sizes: </span>
                <?php foreach($args['ensemble_size'] as $option) { ?>
                    <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"> <?php echo $option; ?> </span>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if (!empty($args['genres'])) { ?>
            <div>
                <span>Desired Genres: </span>
                <?php foreach($args['genres'] as $option) { ?>
                    <span class="text-12 font-bold px-2 py-0.5 rounded-full bg-yellow-light-50 hover:bg-yellow-light cursor-pointer inline-block"> <?php echo $option; ?> </span>
                <?php } ?>
            </div>
        <?php } ?>


        <!-- Equipment -->
        <?php if (!empty($args['equipment_requirement']) or !empty($args['equipment_details'])) { ?>
            <div class="font-semibold mt-3">Equipment</div>
        <?php } ?>
        <?php if (!empty($args['equipment_requirement'])) { ?>
            <div><?php echo $args['equipment_requirement']; ?></div>
        <?php } ?>
        <?php if (!empty($args['equipment_details'])) { ?>
            <div><?php echo $args['equipment_details']; ?></div>
        <?php } ?>


        <!-- Details -->
        <?php if (!empty($args['details'])) { ?>
            <div class="font-semibold mt-3">Details</div>
        <?php } ?>
        <?php if (!empty($args['details'])) { ?>
            <div><?php echo $args['details']; ?> </div>
        <?php } ?>
    </div>

</div>
