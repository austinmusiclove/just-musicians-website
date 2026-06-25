<span class="text-12 px-2 py-0.5 rounded-full capitalize font-semibold w-fit inline-block whitespace-nowrap bg-red text-white"
      x-data="{ badgeText: '' }"
      x-effect="if (!badgeText) {
          if (has_notification(notifications, 'inquiry_response', '<?php echo $args['proposal_id']; ?>')) {
              badgeText = 'New Response';
          } else if (has_notification(notifications, 'inquiry_response_update', '<?php echo (int) $args['proposal_id']; ?>')) {
              badgeText = 'Response Updated';
          }
      }"
      x-show="badgeText"
      x-text="badgeText"
      x-cloak>
</span>
