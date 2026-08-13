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
$lpc = isset($_GET['lpc']) ? sanitize_text_field(wp_unslash($_GET['lpc'])) : '';

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
                createNewListing: <?php echo $current_user_id ? 'false' : 'true'; ?>,
                showApplication: true,
                eventAvailability: {},
                savedProposals: <?php echo clean_arr_for_doublequotes($proposals_map ?? []); ?>,
            }"
            x-on:hideform="showApplication = false;"
        >

            <?php if (empty($lpc)) { ?>

                <h1 class="font-bold text-25 mb-4" x-show="showApplication" x-cloak data-testid="musician-application-title"><?php echo esc_html($title); ?></h1>

                <?php if ($description) { ?>
                    <div class="mb-8 text-16 text-black/80" x-show="showApplication" x-cloak data-testid="musician-application-description"><?php echo wpautop(wp_kses_post($description)); ?></div>
                <?php } ?>

                <?php echo get_template_part('template-parts/applications/musician-application/musician-application-form', '', [
                    'application_id'  => $application_id,
                    'user_listings'   => $user_listings,
                    'events'          => $events,
                    'demo'            => false,
                ]); ?>

            <?php } else if (!empty($lpc)) {
                // If there is a listing publish code then check if it is valid
                // if valid and user is logged out, ask user to sign up to complete application
                // if valid and user is logged in, process and show success or failure
                $valid_lpc = validate_temporary_code($lpc);
                if (is_wp_error($valid_lpc)) {
                    get_template_part('template-parts/applications/musician-application/invalid-lpc', '', [ 'application_id' => $application_id ]);
                } else if (!$current_user_id) {
                    get_template_part('template-parts/applications/musician-application/successful-submission-anon', '', [ 'title' => $title ]);
                } else {
                    $lpc_result = publish_listing_by_tmp_code($lpc);
                    if (is_wp_error($lpc_result)) {
                        get_template_part('template-parts/applications/musician-application/failed-lpc', '', [ 'application_id' => $application_id, 'error' => $lpc_result, ]);
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
