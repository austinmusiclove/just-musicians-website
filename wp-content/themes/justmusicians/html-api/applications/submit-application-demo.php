<?php
$listing_id = (int) get_query_var('listing-id');
if (!$listing_id) {
    $listing_args = get_sanitized_listing_args();
    if (is_wp_error($listing_args)) {
        $message = 'Error: ' . $listing_args->get_error_message();
        echo '<span x-init="$dispatch(\'error-toast\', { \'message\': \'' . $message . '\'})"></span>';
        exit;
    }
}
?>

<span x-init="$dispatch('success-toast', { 'message': 'Application Submitted Successfully'})"></span>
<span x-init="$dispatch('hideform');"></span>
<?php get_template_part('template-parts/applications/musician-application/successful-submission'); ?>
