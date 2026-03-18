<button type="button" class="shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2"
    x-show="numReviewsToShow < reviewCount" x-cloak
    x-on:click="numReviewsToShow += <?php echo REVIEW_PAGE_SIZE; ?>"
>Show More</button>
