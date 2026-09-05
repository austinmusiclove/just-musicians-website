<?php
/**
 * Embed template for musician application form (bare, no site chrome)
 *
 * @package JustMusicians
 */

$application_id = get_query_var('application-id');
$title = get_post_meta($application_id, 'title', true);
$description = get_post_meta($application_id, 'description', true);
$events = get_application_events($application_id);
$lic = isset($_GET['lic']) ? sanitize_text_field(wp_unslash($_GET['lic'])) : '';

if (!$application_id or !$title) {
    wp_safe_redirect(site_url());
    exit;
}

$current_user_id = get_current_user_id();
$user_listings = $current_user_id ? get_user_listings($current_user_id) : [];
$proposals_map = get_proposals_by_events_listings(array_column($events, 'event_id'), array_keys($user_listings));
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html($title); ?></title>
    <style>#wpadminbar{display:none!important}</style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class('min-h-screen bg-white'); ?>>
    <?php wp_body_open(); ?>

    <?php
    get_template_part('template-parts/global/toasts/success-toast', '', []);
    get_template_part('template-parts/global/toasts/error-toast',   '', []);
    ?>

        <div class="max-w-2xl mx-auto px-4 py-8"
            x-data="{
                listingId: '',
                message: '',
                hasListings: <?php echo count($user_listings) > 0 ? 'true' : 'false'; ?>,
                createNewListing: <?php echo is_user_logged_in() ? 'false' : 'true'; ?>,
                showApplication: true,
                eventAvailability: {},
                savedProposals: <?php echo clean_arr_for_doublequotes($proposals_map ?? []); ?>,
                description:   '<?php echo clean_str_for_doublequotes($description); ?>',
            }"
            x-on:hideform="showApplication = false;"
        >

            <?php if (empty($lic)) { ?>

                <h1 class="font-bold text-25 mb-4" x-show="showApplication" x-cloak><?php echo esc_html($title); ?></h1>

                <?php if ($description) { ?>
                    <div class="mb-8 text-16 text-black/80 whitespace-pre-wrap wysiwyg-content" x-show="showApplication" x-cloak x-html="description"></div>
                <?php } ?>

                <?php get_template_part('template-parts/applications/musician-application/musician-application-form', '', [
                    'application_id'  => $application_id,
                    'user_listings'   => $user_listings,
                    'events'          => $events,
                    'demo'            => false,
                    'embed'           => true,
                ]); ?>

            <?php } else if (!empty($lic)) {
                $valid_lic = validate_temporary_code($lic);
                if (is_wp_error($valid_lic)) {
                    get_template_part('template-parts/applications/musician-application/invalid-lic', '', [ 'application_id' => $application_id ]);
                } else if (!is_user_logged_in()) { ?>
                    <div class="text-center py-16">
                        <h2 class="font-bold text-25 mb-4">Your application has been received!</h2>
                        <p class="text-16 text-black/80">Please check your email to complete your account creation.</p>
                    </div>
                <?php } else {
                    $lic_result = add_listing_by_invitation_code($lic);
                    if (is_wp_error($lic_result)) {
                        get_template_part('template-parts/applications/musician-application/failed-lic', '', [ 'application_id' => $application_id, 'error' => $lic_result, ]);
                    } else {
                        get_template_part('template-parts/applications/musician-application/successful-submission-new-listing', '', []);
                    }
                }
            } ?>

        </div>

<?php wp_footer(); ?>
</body>
</html>
