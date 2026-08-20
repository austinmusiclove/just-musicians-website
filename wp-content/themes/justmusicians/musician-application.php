<?php
/**
 * Template for musician application form
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

get_header();
?>

<div id="page" class="flex flex-col grow">

    <div id="content" class="grow flex flex-col relative">
        <div class="container pt-20 md:pt-32 pb-6 md:pb-12"
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

                <h1 class="font-bold text-25 mb-4" x-show="showApplication" x-cloak data-testid="musician-application-title"><?php echo esc_html($title); ?></h1>

                <?php if ($description) { ?>
                    <div class="mb-8 text-16 text-black/80 whitespace-pre-wrap wysiwyg-content" x-show="showApplication" x-cloak x-html="description" data-testid="musician-application-description"></div>
                <?php } ?>

                <?php echo get_template_part('template-parts/applications/musician-application/musician-application-form', '', [
                    'application_id'  => $application_id,
                    'user_listings'   => $user_listings,
                    'events'          => $events,
                    'demo'            => false,
                ]); ?>

            <?php } else if (!empty($lic)) {
                // If there is a listing publish code then check if it is valid
                // if valid and user is logged out, ask user to sign up to complete application
                // if valid and user is logged in, process and show success or failure
                $valid_lic = validate_temporary_code($lic);
                if (is_wp_error($valid_lic)) {
                    get_template_part('template-parts/applications/musician-application/invalid-lic', '', [ 'application_id' => $application_id ]);
                } else if (!is_user_logged_in()) {
                    get_template_part('template-parts/applications/musician-application/successful-submission-anon', '', [ 'title' => $title ]);
                } else {
                    $lic_result = add_listing_by_invitation_code($lic);
                    if (is_wp_error($lic_result)) {
                        get_template_part('template-parts/applications/musician-application/failed-lic', '', [ 'application_id' => $application_id, 'error' => $lic_result, ]);
                    } else {
                        get_template_part('template-parts/applications/musician-application/successful-submission-new-listing', '', []);
                    }
                }
            } ?>

        </div>
    </div>
</div>

<?php
get_footer();
