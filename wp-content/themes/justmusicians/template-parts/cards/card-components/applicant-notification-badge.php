<span class="text-12 px-2 py-0.5 rounded-full capitalize font-semibold w-fit inline-block whitespace-nowrap bg-red text-white"
      x-data="{ badgeText: '' }"
      x-effect="if (!badgeText) {
          if ((notifications?.inquiry_response_proposal_ids ?? []).includes('<?php echo $args['proposal_id']; ?>')) {
              badgeText = 'New Response';
          } else if ((notifications?.inquiry_response_update_proposal_ids ?? []).includes('<?php echo $args['proposal_id']; ?>')) {
              badgeText = 'Unread Update';
          }
      }"
      x-show="badgeText"
      x-text="badgeText"
      x-cloak>
</span>
