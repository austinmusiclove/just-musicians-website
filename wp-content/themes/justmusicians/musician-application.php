<?php
/**
 * Template for musician application form
 *
 * @package JustMusicians
 */

$application_id = get_query_var('application-id');
$title = get_post_meta($application_id, 'title', true);
$description = get_post_meta($application_id, 'description', true);

if (!$application_id || !$title) {
    wp_safe_redirect(site_url());
    exit;
}

$current_user_id = get_current_user_id();
$user_listings   = $current_user_id ? get_user_listings($current_user_id) : [];

get_header();
?>

<div id="page" class="flex flex-col grow">

    <div id="content" class="grow flex flex-col relative">
        <div class="container pt-20 md:pt-32 pb-6 md:pb-12"
            x-data="{
                listingId: '',
                message: '',
                hasListings: <?php echo count($user_listings) > 0 ? 'true' : 'false'; ?>,
                createNewListing: false,
            }"
        >

            <h1 class="font-bold text-25 mb-4"><?php echo esc_html($title); ?></h1>

            <?php if ($description) { ?>
                <div class="mb-8 text-16 text-black/80"><?php echo wpautop(esc_html($description)); ?></div>
            <?php } ?>

            <?php if (!is_user_logged_in()) { ?>

                <?php echo get_template_part('template-parts/global/empty-states/sign-up-to-access', '', [ 'message' => 'submit an application' ]); ?>

            <?php } else { ?>

                <?php echo get_template_part('template-parts/applications/musician-application/musician-application-form', '', [
                    'current_user_id' => $current_user_id,
                    'user_listings'   => $user_listings,
                ]); ?>

            <?php } ?>

        </div>
    </div>
</div>

<?php
get_footer();
