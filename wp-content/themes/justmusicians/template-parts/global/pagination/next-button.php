<a id="<?php echo $args['id']; ?>" class="flex p-1 items-center justify-center rounded border border-solid border-black/10 text-16 text-black hover:bg-yellow/10"

    href="<?php echo esc_url(site_url(add_query_arg('lpage', $args['page'], $args['pagination_base']))); ?>"
    aria-label="Next Page"

    hx-get="<?php echo esc_url(site_url('/wp-html/v1/' . $args['req_path'] . '/?page=' . $args['page'])); ?>"
    hx-trigger="click once"
    hx-target="<?php echo esc_attr($args['target']); ?>"
    hx-include="#hx-form"
    hx-params="not reorder_ids[]"
    hx-sync="closest #hx-form:abort"
    hx-indicator="this"
    hx-push-url="<?php echo esc_url(site_url(add_query_arg('lpage', $args['page'], $args['pagination_base']))); ?>"

    preload="next-btn-revealed"
    x-intersect.dwell.500ms.once="$dispatch('next-btn-revealed')"
>

    <div class="htmx-indicator-component-block-replace">Next</div>
    <div class="htmx-indicator-component-block">
        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '6', 'color' => 'yellow']); ?>
    </div>

</a>
