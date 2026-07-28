<?php if ($args['events']) { ?>
    <div class="flex flex-col gap-2">


        <h2 class="text-20 sm:text-25 font-bold mb-4">Availability</h2>


        <?php foreach ($args['events'] as $event) { ?>

            <div class="flex items-center justify-between gap-4">

                <div class="flex flex-col gap-1">
                    <span class="text-14 sm:text-16 font-semibold"><?php echo esc_html($event['event_name']); ?></span>
                    <span class="text-12 sm:text-14 text-black/60"><?php echo $event['start_date'] ? gmdate('M j, Y', strtotime($event['start_date'])) : ''; ?></span>
                </div>

                <div class="flex gap-2 shrink-0">
                    <label class="cursor-pointer px-3 py-1 rounded-full border border-black/20 text-14 active:bg-navy active:text-white"
                        :class="eventAvailability[<?php echo $event['event_id']; ?>] == 'available' ? 'bg-navy text-white' : 'bg-transparent text-black hover:bg-navy-light hover:text-black'"
                    >
                        <input type="radio" name="event_availability[<?php echo $event['event_id']; ?>]" value="available" class="sr-only" x-model="eventAvailability[<?php echo $event['event_id']; ?>]" required>Available
                    </label>
                    <label class="cursor-pointer px-3 py-1 rounded-full border border-black/20 text-14 active:bg-navy active:text-white"
                        :class="eventAvailability[<?php echo $event['event_id']; ?>] == 'unavailable' ? 'bg-navy text-white' : 'bg-transparent text-black hover:bg-navy-light hover:text-black'"
                    >
                        <input type="radio" name="event_availability[<?php echo $event['event_id']; ?>]" value="unavailable" class="sr-only" x-model="eventAvailability[<?php echo $event['event_id']; ?>]" required>Unavailable
                    </label>
                </div>

            </div>

        <?php } ?>


    </div>
<?php } ?>
