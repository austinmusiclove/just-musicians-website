<?php

function un_simple_pagination() {
    global $wp_query;

    if ($wp_query->max_num_pages <= 1) {
        return; // No need for pagination if there's only one page
    }

    $big = 999999999; // Arbitrary large number for proper URL formatting

    $pagination_links = paginate_links([
        'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format'    => '?paged=%#%',
        'current'   => max(1, get_query_var('paged')),
        'total'     => $wp_query->max_num_pages,
        'prev_text' => 'Previous',
        'next_text' => 'Next',
        'type'      => 'list', 
    ]);

    
    if ($pagination_links) {
        echo '<nav class="pagination">';
        echo $pagination_links;
        echo '</nav>';
    }
    

}
