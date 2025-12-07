<?php
/**
 * The template for displaying venues
 *
 * @package JustMusicians
 */

get_header();

echo get_template_part('template-parts/venues/hero', '', []);
echo get_template_part('template-parts/venues/content', '', []);

get_footer();
