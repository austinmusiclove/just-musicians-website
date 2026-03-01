<div class="overflow-x-auto"
    x-data="{
        venues: <?php echo clean_arr_for_doublequotes($args['venues']); ?>,
    }"
    x-init="console.log(venues);"
>
    <table class="w-full border-collapse text-left">

        <!-- Table head -->
        <?php echo get_template_part('template-parts/musician-earnings-database/venue-table/venue-table-head', '', []); ?>

        <!-- Table body -->
        <tbody>
            <?php foreach ($args['venues'] as $venue) :
                //echo get_template_part('template-parts/musician-earnings-database/venue-table/venue-table-row', '', [ 'venue' => $venue ]);
            endforeach; ?>

            <template x-for="venue in venues" :key="venue.ID">
                <tr class="border-b border-gray-300 hover:bg-gray-50">

                    <td class="py-3 pr-4">
                        <a x-bind:href="venue.link" class="text-navy hover:underline font-medium" x-text="venue.name"></a>
                    </td>

                    <td class="py-3 pr-4" x-text="venue.total_reports"></td>
                    <td class="py-3 pr-4" x-text="'$' + venue.median_earnings"></td>
                    <td class="py-3 pr-4" x-text="'$' + venue.average_earnings"></td>
                    <td class="py-3 pr-4" x-text="venue.avg_ensemble_size"></td>
                    <td class="py-3 pr-4" x-text="venue.avg_set_length"></td>
                    <td class="py-3 pr-4" x-text="venue.payment_method"></td>
                    <td class="py-3" x-text="venue.payment_speed"></td>

                </tr>
            </template>
        </tbody>

    </table>
</div>
