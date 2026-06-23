<span class="text-12 px-2 py-0.5 rounded-full capitalize font-semibold w-fit inline-block whitespace-nowrap"
      x-show="<?php echo $args['var']; ?>"
      :class="{
          'bg-navy text-white': <?php echo $args['var']; ?> === 'available',
          'bg-red text-white':  <?php echo $args['var']; ?> === 'unavailable',
          'bg-yellow/40':       !['available', 'unavailable'].includes(<?php echo $args['var']; ?>),
      }"
      x-text="({
          available:   'Available',
          unavailable: 'Unavailable',
          inquiry:     'No Response',
      }[<?php echo $args['var']; ?>] || <?php echo $args['var']; ?>)"
      x-cloak
></span>
