<a id="<?php echo $args['id']; ?>"
    href="<?php echo esc_url(site_url(add_query_arg('lpage', $args['page'], $args['pagination_base']))); ?>"

    <?php if (isset($args['aria_label'])) { ?>
        aria-label="<?php echo $args['aria_label']; ?>"
    <?php } else { ?>
        aria-label="Go to page <?php echo $args['page']; ?>"
    <?php } ?>

    hx-get="<?php echo esc_url(site_url('/wp-html/v1/' . $args['req_path'] . '/?page=' . $args['page'])); ?>"
    hx-trigger="click once"
    hx-target="<?php echo esc_attr($args['target']); ?>"
    hx-include="#hx-form"
    hx-params="not reorder_ids[]"
    hx-sync="closest #hx-form:abort"
    hx-indicator="this"
    hx-push-url="<?php echo esc_url(site_url(add_query_arg('lpage', $args['page'], $args['pagination_base']))); ?>"

    <?php if (isset($args['preload'])) { ?>
        preload="<?php echo $args['preload']; ?>"
    <?php } else { ?>
        preload="prev-btn-revealed"
        x-intersect.dwell.500ms.once="$dispatch('prev-btn-revealed')"
    <?php } ?>
>

    <div class="htmx-indicator-component-block-replace"><?php echo $args['label']; ?></div>
    <div class="htmx-indicator-component-block">
        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '6', 'color' => 'yellow']); ?>
    </div>

</a>
