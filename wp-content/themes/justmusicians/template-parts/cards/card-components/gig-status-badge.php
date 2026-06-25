<span class="text-12 px-2 py-0.5 rounded-full capitalize font-semibold w-fit inline-block whitespace-nowrap"
      x-data="{ hasNotification: false }"
      x-effect="if (!hasNotification) {
          if (has_notification(notifications, 'new_inquiry', '<?php echo $args['proposal_id']; ?>')) {
              hasNotification = true;
          }
      }"
      x-show="<?php echo $args['status_var']; ?> && !hasNotification" x-cloak
      :class="{
          'bg-red/40':    <?php echo $args['status_var']; ?> === 'inquiry',
          'bg-yellow/40': !['inquiry'].includes(<?php echo $args['status_var']; ?>),
      }"
      x-text="({
          available:   'Responded',
          unavailable: 'Responded',
          inquiry:     'Inquiry',
      }[<?php echo $args['status_var']; ?>] || <?php echo $args['status_var']; ?>)"
></span>
