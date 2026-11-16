<?php

// Output Organization schema in the head
function hm_output_organization_schema() {
    get_template_part('template-parts/global/schema/organization-schema');
}
add_action('wp_head', 'hm_output_organization_schema');
