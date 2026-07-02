<span class="text-12 px-2 py-0.5 rounded-full capitalize font-semibold w-fit inline-block whitespace-nowrap bg-red text-white"
      x-data="{ badgeText: '' }"
      x-effect="if (!badgeText) {
          if (has_notification(notifications, 'new_applicant', '<?php echo $args['submission_id']; ?>')) {
              badgeText = 'New Applicant';
          }
      }"
      x-show="badgeText" x-cloak
      x-text="badgeText"
>
</span>
