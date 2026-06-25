<span class="text-12 px-2 py-0.5 rounded-full capitalize font-semibold w-fit inline-block whitespace-nowrap"
      x-show="<?php echo $args['status_var']; ?>"
      :class="{
          'bg-navy text-white': <?php echo $args['status_var']; ?> === 'available',
          'bg-red/40':          <?php echo $args['status_var']; ?> === 'unavailable',
          'bg-yellow/40':       !['available', 'unavailable'].includes(<?php echo $args['status_var']; ?>),
      }"
      x-text="({
          available:   'Available',
          unavailable: 'Unavailable',
          inquiry:     'No Response',
          stale:       'Awaiting Update',
      }[<?php echo $args['status_var']; ?>] || <?php echo $args['status_var']; ?>)"
      x-cloak
></span>
