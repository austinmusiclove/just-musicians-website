<span
    x-data="{
        numReviewsToShow: <?php echo REVIEW_PAGE_SIZE; ?>,
        reviewCount: <?php echo $args['review_count']; ?>,
    }"
>

<?php if ($args['review_count'] > 0) {

    foreach($args['reviews'] as $index => $review) {
        echo get_template_part('template-parts/reviews/basic-review', '', [
            'index'               => $index,
            'rating'              => $review['rating'],
            'review'              => $review['review'],
            'date'                => $review['date'],
            'author_name'         => $review['author_name'],
            'author_organization' => $review['author_organization'],
            'author_position'     => $review['author_position'],
            'author_image_url'    => $review['author_image_url'],
            'author_id'           => $review['author_id'],
        ]);
    }
    echo get_template_part('template-parts/reviews/show-more-button', '', []);
    echo get_template_part('template-parts/reviews/write-review-button', '', []);

} else {

    echo get_template_part('template-parts/reviews/no-reviews', '', [] );

} ?>

</span>
