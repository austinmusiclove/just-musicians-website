<!-- Rating Stars Input -->
<div
    id="rating-stars"
    class="flex gap-x-1 text-yellow w-48 sm:w-64 my-4 sm:my-8 cursor-pointer"
    data-selected="1"
>
    <?php for ($index = 1; $index <= 5; $index++): ?>
        <div class="star w-8 sm:w-24" data-value="<?php echo $index; ?>">
            <?php echo get_template_part('template-parts/reviews/rating-star', '', [ 'fill_percentage' => ($index === 1 ? 100 : 0) ]); ?>
        </div>
    <?php endfor; ?>
</div>

<input type="hidden" name="rating" id="rating-input" value="1">
