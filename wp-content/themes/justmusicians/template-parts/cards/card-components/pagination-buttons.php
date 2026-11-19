<?php
$prev_page = $args['prev_page'] ?? null;
$next_page = $args['next_page'] ?? null;
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
    >
        <button class="prev-indicator">
            <span class="htmx-indicator-component-block-replace">Previous</span>
            <span class="htmx-indicator-component-block">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '6', 'color' => 'yellow']); ?>
            </span>
        </button>
    </a>
<? } ?>

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
    >
        <button class="next-indicator">
            <span class="htmx-indicator-component-block-replace">Next</span>
            <span class="htmx-indicator-component-block">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '6', 'color' => 'yellow']); ?>
            </span>
        </button>
    </a>
<? } ?>
