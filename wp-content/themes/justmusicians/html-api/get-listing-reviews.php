<?php

// Get reviews

$reviews = [];
$review_count = 6;
$average_rating = 2.3;

?>

<span id="review-count" hx-swap-oob="outerHTML"><?php echo $review_count; ?></span>
<div id="average-rating" class="flex gap-x-1 text-yellow w-24" hx-swap-oob="outerHTML">
    <?php echo get_template_part('template-parts/global/ratings/rating-stars', '', [ 'rating' => $average_rating, ]); ?>
</div>


<?php

// Iterate reviews
// Or no reviews state if no reviews

echo get_template_part('template-parts/global/review', '', [
    'rating'           => 3.5,
    'review'           => 'Qui dolor enim consectetur do et non ex amet culpa sint in ea non dolore. Enim minim magna anim id minim eu cillum sunt dolore aliquip. Amet elit laborum culpa irure incididunt adipisicing culpa amet officia exercitation. Eu non aute velit id velit Lorem elit anim pariatur.',
    'author'           => 'John Filippone',
    'author_title'     => 'Talent Buyer at Austin Music Love',
    'author_image_url' => 'https://hiremoremusicians.com/wp-content/uploads/2025/09/instagram-small.webp',
]);

?>
