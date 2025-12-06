<?php

$rating = $args['rating']; // float 0–5

$fill_1 = min(100, max(0, ($rating - 0) * 100));
$fill_2 = min(100, max(0, ($rating - 1) * 100));
$fill_3 = min(100, max(0, ($rating - 2) * 100));
$fill_4 = min(100, max(0, ($rating - 3) * 100));
$fill_5 = min(100, max(0, ($rating - 4) * 100));

echo get_template_part('template-parts/reviews/stars/rating-star', '', [ 'fill_percentage' => $fill_1, ]);
echo get_template_part('template-parts/reviews/stars/rating-star', '', [ 'fill_percentage' => $fill_2, ]);
echo get_template_part('template-parts/reviews/stars/rating-star', '', [ 'fill_percentage' => $fill_3, ]);
echo get_template_part('template-parts/reviews/stars/rating-star', '', [ 'fill_percentage' => $fill_4, ]);
echo get_template_part('template-parts/reviews/stars/rating-star', '', [ 'fill_percentage' => $fill_5, ]);

?>
