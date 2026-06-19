<ul class="absolute z-10 top-full left-0 w-full bg-white border border-black/40 rounded-md shadow-sm max-h-32 overflow-y-scroll" style="margin-top: -1px">

    <!-- Locations -->
    <?php foreach((array) $args['locations'] as $location) { ?>
        <li class="p-2 hover:bg-yellow-10 cursor-pointer" x-on:click="_updateInquiryLocation(<?php echo clean_arr_for_doublequotes($location); ?>)">
            <span><?php echo $location['label'] . ', ' . $location['country']; ?></span>
        </li>

    <?php } ?>

</ul>
