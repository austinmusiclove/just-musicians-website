<div class="py-6 flex items-center justify-center page-indicator htmx-indicator-flex-replace" id="load-more-<?php echo $args['post_id']; ?>">


    <button type="button" class="hover:bg-yellow-light bg-yellow font-sun-motter cursor-pointer px-6 py-3 rounded-sm text-16 inline-block"
        preload="load-more-revealed-<?php echo $args['post_id']; ?>"
        x-intersect.once="$dispatch('load-more-revealed-<?php echo $args['post_id']; ?>')"
        hx-get="<?php echo site_url('/wp-html/v1/' . $args['req_path'] . '/?page=' . $args['next_page']); ?>"
        hx-trigger="click once"
        hx-target="#load-more-<?php echo $args['post_id']; ?>"
        hx-swap="outerHTML"
        hx-include="#hx-form"
        hx-params="not reorder_ids[]"
        hx-sync="closest #hx-form:abort"
        hx-indicator=".page-indicator"
    >
        Load More Results
    </button>


</div>
