<?php
/**
 * The template for displaying glossary pages
 *
 * @package JustMusicians
 */

get_header();

$terms = get_field('terms') ?: [];
$glossary_id = get_the_ID();

usort($terms, function($a, $b) {
    return strcasecmp(get_the_title($a), get_the_title($b));
});

$terms_by_letter = [];
foreach ($terms as $term_id) {
    $title = get_the_title($term_id);
    $letter = strtoupper(substr($title, 0, 1));
    if (!isset($terms_by_letter[$letter])) {
        $terms_by_letter[$letter] = [];
    }
    $terms_by_letter[$letter][] = [
        'id' => $term_id,
        'title' => $title,
        'definition' => get_field('definition', $term_id),
    ];
}
ksort($terms_by_letter);

$alphabet = range('A', 'Z');

?>

<header class="bg-yellow-light pt-12 md:pt-24 relative overflow-hidden pb-6 md:pb-14">
    <div class="container grid grid-cols-1 sm:grid-cols-10 gap-x-8 md:gap-x-24 gap-y-2 md:gap-y-10">
        <div class="sm:col-span-7 pr-8 sm:pr-0 flex flex-col gap-8">

            <h1 class="font-bold text-40 sm:text-57"><?php the_title(); ?></h1>

            <?php if (has_excerpt()) : ?>
                <p class="text-22"><?php echo get_the_excerpt(); ?></p>
            <?php endif; ?>

            <!-- A-Z Filter -->
            <div class="flex flex-wrap gap-2">
                <?php foreach ($alphabet as $letter) : ?>
                    <?php if (isset($terms_by_letter[$letter])) : ?>
                        <a href="#letter-<?php echo $letter; ?>" class="flex items-center justify-center rounded text-sm hover:underline">
                            <?php echo $letter; ?>
                        </a>
                        <?php if ($letter != "Z") : ?><span class="text-grey">|</span><? endif; ?>
                    <?php else : ?>
                        <span class="flex items-center justify-center rounded text-grey text-sm">
                            <?php echo $letter; ?>
                        </span>
                            <?php if ($letter != "Z") : ?><span class="text-grey">|</span><? endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</header>

<div class="container lg:grid lg:grid-cols-10 gap-24 py-8">
    <div class="col lg:col-span-7 mb-8 lg:mb-0">

        <!-- All Terms by Letter -->
        <?php foreach ($terms_by_letter as $letter => $letter_terms) : ?>
            <div id="letter-<?php echo $letter; ?>" class="mb-8">
                <h2 class="font-bold text-32 mb-4 border-b border-grey pb-2"><?php echo $letter; ?></h2>
                <ul class="space-y-3">
                    <?php foreach ($letter_terms as $term) : ?>
                        <li>
                            <a href="<?php echo add_query_arg('from_glossary', $glossary_id, get_permalink($term['id'])); ?>" class="block rounded">
                                <h3 class="text-18 hover:underline"><?php echo $term['title']; ?></h3>
                                <!-- <?php //if ($term['definition']) : ?>
                                    <p class="text-grey text-sm mt-1"><?php //echo wp_trim_words($term['definition'], 30); ?></p>
                                <?php //endif; ?> -->
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>

    </div>
    <div class="col lg:col-span-3 relative">
        <div class="sticky top-24">
            <?php echo get_template_part('template-parts/inquiries/inquiry-sidebar', '', array(
                'button_color' => 'bg-navy text-white hover:bg-yellow hover:text-black',
                'responsive' => 'lg:border-none lg:p-0'
            )); ?>
        </div>
    </div>
</div>

<?php
get_footer();
