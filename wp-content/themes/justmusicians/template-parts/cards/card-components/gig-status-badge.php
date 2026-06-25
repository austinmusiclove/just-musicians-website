<span class="text-12 px-2 py-0.5 rounded-full capitalize font-semibold w-fit inline-block whitespace-nowrap"
      x-data="{ showStatus: true }"
      x-effect="if (showStatus) {
          if (has_notification(notifications, 'new_inquiry', '<?php echo $args['proposal_id']; ?>')) {
              showStatus = false;
          }
      }"
      x-show="<?php echo $args['status_var']; ?> && showStatus" x-cloak
      :class="{
          'bg-red/40':         <?php echo $args['status_var']; ?> === 'inquiry',
          'bg-red text-white': <?php echo $args['status_var']; ?> === 'stale',
          'bg-yellow/40':      !['inquiry', 'stale'].includes(<?php echo $args['status_var']; ?>),
      }"
      x-text="({
          available:   'Responded',
          unavailable: 'Responded',
          inquiry:     'Inquiry',
          stale:       'Availability Requested',
      }[<?php echo $args['status_var']; ?>] || <?php echo $args['status_var']; ?>)"
></span>
