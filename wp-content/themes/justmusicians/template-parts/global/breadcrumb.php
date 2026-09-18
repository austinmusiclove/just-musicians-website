<?php

$items = $args['items'] ?? [];

if (empty($items)) {
    return;
}

?>

<nav aria-label="Breadcrumb" class="text-sm mb-4">
    <ol class="flex flex-wrap gap-2">

        <?php foreach ($items as $i => $item) {
            $last = ($i === count($items) - 1); ?>

            <li>
                <?php if ($last || empty($item['url'])) { ?>
                    <span aria-current="<?php echo $last ? 'page' : ''; ?>"><?php echo esc_html($item['label']); ?></span>
                <?php } else { ?>
                    <a href="<?php echo esc_url($item['url']); ?>" class="hover:underline"><?php echo esc_html($item['label']); ?></a>
                <?php } ?>
            </li>

            <?php if (!$last) { ?>
                <li aria-hidden="true">/</li>
            <?php } ?>

        <?php } ?>

    </ol>
</nav>

<?php get_template_part('template-parts/global/schema/breadcrumb', '', array('items' => $items)); ?>
