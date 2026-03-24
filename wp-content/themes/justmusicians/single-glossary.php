<?php
/**
 * The template for displaying glossary pages
 *
 * @package JustMusicians
 */

get_header();

$terms = get_field('terms') ?: [];
$glossary_id = get_the_ID();

$parent_glossary = get_post_meta($glossary_id, 'parent_glossary', true)[0];
$parent_glossary_title = $parent_glossary ? get_the_title($parent_glossary) : '';
$parent_glossary_link = $parent_glossary ? get_permalink($parent_glossary) : '';

usort($terms, function($a, $b) {
    return strcasecmp(get_the_title($a), get_the_title($b));
});

$terms_by_letter = [];
$numeric_terms = [];
$alphabetical_terms = [];

foreach ($terms as $term_id) {
    $title = get_the_title($term_id);
    // Check if the first character is a digit
    if (is_numeric(substr($title, 0, 1))) {
        $numeric_terms[] = [
            'id' => $term_id,
            'title' => $title,
            'definition' => get_field('definition', $term_id),
        ];
    } else {
        $alphabetical_terms[] = $term_id;
    }
}

// Add numeric terms to terms_by_letter under '#' key
if (!empty($numeric_terms)) {
    $terms_by_letter['#'] = $numeric_terms;
}

// Process remaining alphabetical terms
foreach ($alphabetical_terms as $term_id) {
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
        <div class="sm:col-span-8 pl-4 pr-8 sm:pr-0 flex flex-col gap-8">

            <?php if ($parent_glossary) : ?>
            <div class="inline-flex items-center gap-1 mb-4">
                <a href="<?php echo $parent_glossary_link; ?>" class="hover:underline">
                    <span><?php echo esc_html($parent_glossary_title); ?></span>
                </a>
                <img class="h-8 rotate-180" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron-left.svg'; ?>" />
                <span class="font-bold"><?php the_title(); ?></span>
            </div>
            <?php endif ?>

            <h1 class="font-bold text-40 sm:text-57"><?php the_title(); ?></h1>

            <?php if (has_excerpt()) : ?>
                <p class="text-22"><?php echo get_the_excerpt(); ?></p>
            <?php endif; ?>

            <!-- A-Z Filter -->
            <div class="flex flex-wrap gap-2">
                <?php if (isset($terms_by_letter['#'])) : ?>
                    <a href="#letter-#" class="flex items-center justify-center rounded text-sm hover:underline">#</a>
                    <?php if (!empty($alphabetical_terms)) : ?><span class="opacity-20">|</span><?php endif; ?>
                <?php endif; ?>
                <?php foreach ($alphabet as $index => $letter) : ?>
                    <?php if (isset($terms_by_letter[$letter])) : ?>
                        <a href="#letter-<?php echo $letter; ?>" class="flex items-center justify-center rounded text-sm hover:underline"><?php echo $letter; ?></a>
                        <?php if ($index < count($alphabet) - 1) : ?><span class="opacity-20">|</span><?php endif; ?>
                    <?php else : ?>
                        <span class="flex items-center justify-center rounded opacity-20 text-sm"><?php echo $letter; ?></span>
                        <?php if ($index < count($alphabet) - 1) : ?><span class="opacity-20">|</span><?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</header>

<div class="container lg:grid lg:grid-cols-10 gap-24 p-8">
    <div class="col lg:col-span-7 mb-8 lg:mb-0 flex flex-col gap-16">

        <!-- All Terms by Letter -->
        <?php foreach ($terms_by_letter as $letter => $letter_terms) : ?>
            <div id="letter-<?php echo $letter; ?>" class="flex flex-col gap-16">
                <h2 class="font-bold text-40"><?php echo $letter; ?></h2>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <?php foreach ($letter_terms as $term) : ?>
                        <li>
                            <a href="<?php echo add_query_arg('from_glossary', $glossary_id, get_permalink($term['id'])); ?>" class="block rounded">
                                <h3 class="text-20 hover:underline"><?php echo $term['title']; ?></h3>
                                <?php if ($term['definition']) : ?>
                                    <p class="text-grey text-sm mt-1"><?php echo wp_trim_words($term['definition'], 12); ?></p>
                                <?php endif; ?>
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
