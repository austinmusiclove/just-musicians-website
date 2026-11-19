<?php
$current_page  = $args['current_page'] ?? null;
$max_num_pages = $args['max_num_pages'] ?? null;
$prev_page = $current_page > 1 ? $current_page - 1 : null;
$next_page = $current_page < $max_num_pages ? $current_page + 1 : null;
?>

<?php if ($prev_page) { ?>
    <a id="prev-btn-<?php echo $args['post_id']; ?>"
        href="<?php echo site_url(add_query_arg( 'lpage', $prev_page, $args['pagination_base'] )); ?>"
        hx-get="<?php echo site_url('/wp-html/v1/' . $args['req_path'] . '/?page=' . $prev_page); ?>"
        hx-trigger="click once"
        hx-target="<?php echo $args['target'] ?? '#results'; ?>"
        hx-include="#hx-form"
        hx-params="not reorder_ids[]"
        hx-sync="closest #hx-form:abort"
        hx-indicator=".prev-indicator"
        hx-push-url="<?php echo site_url(add_query_arg( 'lpage', $prev_page, $args['pagination_base'] )); ?>"
    >
        <button class="prev-indicator">
            <span class="htmx-indicator-component-block-replace">Previous</span>
            <span class="htmx-indicator-component-block">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '6', 'color' => 'yellow']); ?>
            </span>
        </button>
    </a>
<? } ?>

<span>Page <?php echo $current_page; ?></span>

<?php if ($next_page) { ?>
    <a id="next-btn-<?php echo $args['post_id']; ?>"
        href="<?php echo site_url(add_query_arg( 'lpage', $next_page, $args['pagination_base'] )); ?>"
        hx-get="<?php echo site_url('/wp-html/v1/' . $args['req_path'] . '/?page=' . $next_page); ?>"
        hx-trigger="click once"
        hx-target="<?php echo $args['target'] ?? '#results'; ?>"
        hx-include="#hx-form"
        hx-params="not reorder_ids[]"
        hx-sync="closest #hx-form:abort"
        hx-indicator=".next-indicator"
        hx-push-url="<?php echo site_url(add_query_arg( 'lpage', $next_page, $args['pagination_base'] )); ?>"
    >
        <button class="next-indicator">
            <span class="htmx-indicator-component-block-replace">Next</span>
            <span class="htmx-indicator-component-block">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '6', 'color' => 'yellow']); ?>
            </span>
        </button>
    </a>
<? } ?>
