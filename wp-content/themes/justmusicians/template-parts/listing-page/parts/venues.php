
<?php if (!empty($args['venues_combined']) or $args['is_preview']) { ?>

<div id="venues" <?php if ($args['is_preview']) { ?> x-show="allVenuesPlayed.length > 0" x-cloak <?php } ?> >


    <h2 class="text-22 font-bold mb-5">Venues played</h2>
    <div class="flex items-center gap-2 flex-wrap">

        <!-- Preview version -->
        <?php if ($args['is_preview']) { ?>
            <template x-for="(venue, index) in allVenuesPlayed" :key="index">

                <div class="bg-yellow-light p-2 rounded text-16 flex flex-col items-start gap-0.5">
                    <span class="font-bold" x-text="venue['name']"></span>
                    <span x-text="venue['street_address']"></span>
                    <span x-text="venue['address_locality'] + ', ' + venue['address_region'] + ' ' + venue['postal_code']"></span>
                </div>

            </template>


        <!-- Listing page version -->
        <?php } else {
            foreach($args['venues_combined'] as $venue) { ?>

                <div class="bg-yellow-light p-2 rounded text-16 flex flex-col items-start gap-0.5">
                    <span class="font-bold"><?php echo $venue['name']; ?></span>
                    <span><?php echo $venue['street_address']; ?><br/><?php echo $venue['address_locality'] . ', ' . $venue['address_region'] . ' ' . $venue['postal_code']; ?></span>
                </div>

            <?php } ?>
        <?php } ?>


    </div>
</div>

<?php } ?>
