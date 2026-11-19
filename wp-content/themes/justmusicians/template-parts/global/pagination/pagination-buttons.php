<?php
$current_page    = max(1, (int) ($args['current_page'] ?? 1));
$max_num_pages   = max(1, (int) ($args['max_num_pages'] ?? 1));
$pagination_base = $args['pagination_base'] ?? site_url();
$req_path        = $args['req_path'] ?? 'listings';
$target          = $args['target'] ?? '#results';

$prev_page = $current_page > 1 ? $current_page - 1 : null;
$next_page = $current_page < $max_num_pages ? $current_page + 1 : null;

$page_numbers = array_unique(array_merge(
    [1, $max_num_pages],
    range(max(1, $current_page - 2), min($max_num_pages, $current_page + 2))
));
$page_numbers = array_values(array_filter($page_numbers, function ($page) use ($max_num_pages) {
    return $page >= 1 && $page <= $max_num_pages;
}));
sort($page_numbers);
?>

<nav class="pagination p-4">
    <div class="flex flex-wrap items-center justify-center gap-2">
        <?php if ($prev_page) {
            echo get_template_part('template-parts/global/pagination/page-button', '', [
                'id'              => 'prev-btn',
                'label'           => 'Previous',
                'aria_label'      => 'Previous Page',
                'page'            => $prev_page,
                'pagination_base' => $pagination_base,
                'req_path'        => $req_path,
                'target'          => $target,
            ]);
        } ?>

        <div class="page-numbers">
            <?php foreach ($page_numbers as $index => $page) { ?>

                <?php if ($index > 0 && $page > $page_numbers[$index - 1] + 1) { ?>
                    <span aria-hidden="true">…</span>
                <?php } ?>

                <?php if ($page === $current_page) { ?>
                    <span class="current" aria-current="page"><?php echo $page; ?></span>

                <?php } else {
                    echo get_template_part('template-parts/global/pagination/page-button', '', [
                        'id'              => 'page-btn-' . $page,
                        'label'           => $page,
                        'page'            => $page,
                        'pagination_base' => $pagination_base,
                        'req_path'        => $req_path,
                        'target'          => $target,
                        'preload'         => 'mouseover'
                    ]);
                }
            } ?>
        </div>

        <?php if ($next_page) {
            echo get_template_part('template-parts/global/pagination/page-button', '', [
                'id'              => 'next-btn',
                'label'           => 'Next',
                'aria_label'      => 'Next Page',
                'page'            => $next_page,
                'pagination_base' => $pagination_base,
                'req_path'        => $req_path,
                'target'          => $target,
            ]);
        } ?>
    </div>
</nav>
