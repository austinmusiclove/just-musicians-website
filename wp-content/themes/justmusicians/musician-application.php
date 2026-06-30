<?php
/**
 * Template for musician application form
 *
 * @package JustMusicians
 */

$application_id = get_query_var('application-id');
$title = get_post_meta($application_id, 'title', true);
$description = get_post_meta($application_id, 'description', true);

$current_user_id = get_current_user_id();
$user_listings   = $current_user_id ? get_user_listings($current_user_id) : [];

get_header();
?>

<div id="page" class="flex flex-col grow">

    <div id="content" class="grow flex flex-col relative">
        <div class="container pt-20 md:pt-32 pb-6 md:pb-12">

            <?php if (!$application_id || !$title) { ?>

                <p class="text-16 text-black/60">Application not found.</p>

            <?php } ?>

                <h1 class="font-bold text-25 mb-4"><?php echo esc_html($title); ?></h1>

                <?php if ($description) { ?>
                    <div class="mb-8 text-16 text-black/80"><?php echo wpautop(esc_html($description)); ?></div>
                <?php } ?>

                <?php if (!is_user_logged_in()) { ?>

                    <?php echo get_template_part('template-parts/global/empty-states/sign-up-to-access', '', [ 'message' => 'submit an application' ]); ?>

                <?php } else { ?>

                <form class="flex flex-col gap-4" x-data="{
                    hasListings: '<?php echo count($user_listings) > 0 ? 'false' : 'true'; ?>',
                    createNewListing: 'false',
                }">

                    <!-- Listing Dropdown -->
                    <?php if ($current_user_id and count($user_listings) > 0) { ?>
                    <?php get_template_part('template-parts/applications/musician-application/listing-dropdown', '', [
                        'listings' => $user_listings,
                    ]); ?>
                    <?php } ?>

                    <!-- Listing Form -->
                    <div x-show="createNewListing || !hasListings" x-cloak>
                        <?php get_template_part('template-parts/applications/musician-application/listing-form', '', []); ?>
                    </div>

                    <!-- Message Input -->
                    <div>
                        <label for="submission-message" class="block font-bold text-16 mb-2">Personalized Message</label>
                        <textarea id="submission-message" name="message" rows="4" class="w-full px-3 py-2 border border-black/20 rounded-sm text-14" placeholder="Here's your chance to send the application reviewer a personalized message"></textarea>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2 w-full sm:w-fit disabled:opacity-70 disabled:hover:bg-black/40"
                        x-bind:disabled="!loggedIn"
                    >Submit Application</button>

                </form>

            <?php } ?>

        </div>
    </div>
</div>

<?php
get_footer();
