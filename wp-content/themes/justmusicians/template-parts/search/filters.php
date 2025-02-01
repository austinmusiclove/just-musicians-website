<div>
<?php                 
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'title' => 'Genre',
        'tag_1' => 'folk',
        'tag_2' => 'indie rock',
        'tag_3' => 'latin',
        'tag_4' => 'psychedelic',
        'see_all' => false
    ));  
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'title' => 'Type',
        'tag_1' => 'band',
        'tag_2' => 'DJ',
        'tag_3' => 'musician',
        'tag_4' => 'solo/duo',
        'see_all' => false
    )); 
    echo get_template_part('template-parts/filters/elements/tags', '', array(
        'title' => 'Category',
        'tag_1' => 'acoustic',
        'tag_2' => 'cover band',
        'tag_3' => 'producer',
        'tag_4' => 'wedding band',
        'see_all' => true
    )); 
    echo get_template_part('template-parts/filters/location', '', array()); 

    ?>

    <div class="border-b border-black/20 mb-6 pb-6 last:mb-0 last:pb-0 last:border-b-0">
        <h3 class="font-bold text-18 mb-3">Verification</h3>
        <?php echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Verified only',
        )); ?>
    </div>
</div>
