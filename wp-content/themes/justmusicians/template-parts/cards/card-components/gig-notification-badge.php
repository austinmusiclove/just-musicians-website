<span class="text-12 px-2 py-0.5 rounded-full capitalize font-semibold w-fit inline-block whitespace-nowrap bg-red text-white"
      x-data="{ badgeText: '' }"
      x-effect="if (!badgeText) {
          if (has_notification(notifications, 'new_inquiry', '<?php echo $args['proposal_id']; ?>')) {
              badgeText = 'New Inquiry';
          } else if (has_notification(notifications, 'event_dt_change', '<?php echo $args['proposal_id']; ?>')) {
              badgeText = 'Date/Time Changed';
          }
      }"
      x-show="badgeText" x-cloak
      x-text="badgeText"
>
</span>
