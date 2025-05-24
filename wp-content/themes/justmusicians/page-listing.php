<?php
/**
 * The template for displaying the listing form
 *
 * @package JustMusicians
 */

get_header();

?>



    <!-- Hero -->
    <?php echo get_template_part('template-parts/listing/hero', '', array(
       'instance' => 'listing-page'
    )); ?>

    <!-- Content -->
    <?php echo get_template_part('template-parts/listing/content', '', array(
               'instance' => 'listing-page'
    )); ?>



<?php
get_footer();
