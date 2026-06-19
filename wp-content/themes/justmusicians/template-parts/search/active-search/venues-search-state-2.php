<ul class="absolute z-10 top-full left-0 w-full bg-white border border-black/40 rounded-md shadow-sm max-h-32 overflow-y-scroll" style="margin-top: -1px">

    <!-- Venues -->
    <?php foreach((array) $args['venues'] as $venue) { ?>
        <li class="p-2 hover:bg-yellow-10 cursor-pointer" x-on:click="_selectVenue($refs.venuesInput, <?php echo clean_arr_for_doublequotes($venue); ?>)">
            <span><?php echo $venue['name']; ?></span>
            <span> | </span>
            <span class="opacity-50"><?php echo $venue['street_address'] . ', ' . $venue['address_locality'] . ', ' . $venue['address_region'] . ' ' . $venue['postal_code']; ?></span>
        </li>

    <?php } ?>

</ul>
