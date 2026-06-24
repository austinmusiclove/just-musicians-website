<span class="text-12 px-2 py-0.5 rounded-full capitalize font-semibold w-fit inline-block whitespace-nowrap bg-red text-white"
      x-data="{ hasNewResponse: false }"
      x-effect="if (!hasNewResponse && (notifications?.inquiry_response_proposal_ids ?? []).includes('<?php echo $args['proposal_id']; ?>')) { hasNewResponse = true; }"
      x-show="hasNewResponse"
      x-cloak>
    New Response
</span>
