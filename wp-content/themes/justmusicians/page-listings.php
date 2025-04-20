<?php
/**
 * The template for the listings landing page
 *
 * @package JustMusicians
 */

get_header();

?>

<div id="page" class="flex flex-col grow">

        <input type="hidden" name="search" value="" x-bind:value="searchInput" x-init="$watch('searchInput', value => { searchVal = value; $dispatch('filterupdate'); })" />
        <div id="content" class="grow flex flex-col relative">
            <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
                <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                    <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-16 lg:top-20 h-fit">
                      <?php echo get_template_part('template-parts/account/sidebar', '', []); ?>
                    </div>
                </div>
                <div class="col md:col-span-6 py-6 md:py-12">

                    <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                        <h1 class="font-bold text-22 sm:text-25">My Listings</h1>
                        <?php if (is_user_logged_in()) { ?><a href="/listing-form"><button class="font-bold text-12 pt-1.5 pb-1 px-1.5 rounded bg-white border border-black/20 hover:drop-shadow cursor-pointer">Add +</button></a><?php } ?>
                    </div>


                    <!------------ Listing invitation code Toasts ----------------->
                    <div>
                        <?php echo get_template_part('template-parts/global/toasts/error-toast', '', ['event_name' => 'lic-error-toast']); ?>
                        <?php echo get_template_part('template-parts/global/toasts/success-toast', '', ['event_name' => 'lic-success-toast']); ?>
                    </div>
                    <!-- handle listing invitiation -->
                    <?php if (is_user_logged_in()) {
                        if (isset($_GET['lic'])) {
                            // send listing invitation validation with redirect without the param in url to avoid infinite loop
                            $success = add_listing_by_invitation_code($_GET['lic']);
                            // if success, redirect back to the same page but remove query params to avoid an infinite loop
                            if ($success and !is_wp_error($success)) { ?>
                                <span x-init="$dispatch('lic-success-toast', {'message': 'Listing invitation link processed successfully'})"></span>
                            <?php } else { ?>
                                <span x-init="$dispatch('lic-error-toast', {'message': 'Failed to add listing from invitation link with error: <?php echo $success->get_error_message(); ?>'})"></span>
                            <?php }
                        }
                    } ?>



                    <!-- Logged out -->
                    <?php if (!is_user_logged_in()) {
                        if (!empty($_GET['lic']) and !empty($_GET['mdl']) and $_GET['mdl'] == 'signup') { ?>
                            <span x-init="showLoginModal = false; showSignupModal = true; signupModalMessage = 'Sign up to activate this listing invitation link';"></span>
                        <?php } else if (isset($_GET['lic'])) { ?>
                            <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to activate this listing invitation link';"></span>
                        <?php } else { ?>
                            <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to see your listings';"></span>
                        <?php } ?>

                        <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                            <div class="pb-32 relative z-10">
                                <span class="text-18 sm:text-22 block text-center mb-4">Log in to see your listings</span>
                                <button x-on:click="showLoginModal = true;" type="button" data-trigger="quote" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Log In</button>
                            </div>

                            <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                            <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                        </div>


                    <!-- Logged in -->
                    <?php } else {

                        $current_user_id = get_current_user_id();
                        $user_listings = get_user_meta($current_user_id, 'listings', true);
                        if ( $user_listings and count($user_listings) > 0 ) {

                            // Query the posts
                            $args = [
                                'post_type'      => 'listing',
                                'post__in'       => $user_listings,
                                'post_status'    => 'publish',
                                'orderby'        => 'post__in',
                                'posts_per_page' => -1
                            ];
                            $query = new WP_Query($args);
                            if ($query->have_posts()) { ?>

                                <!-- Display user's listings -->
                                <!--
                                <div class="flex items-center justify-between md:justify-start">
                                    <?php //echo get_template_part('template-parts/search/sort', '', [ 'show_number' => false ]); ?>
                                </div>
                                -->

                                <?php while ($query->have_posts()) {
                                    $query->the_post();
                                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'standard-listing');
                                    $listing_genres = get_the_terms(get_the_ID(), 'genre');
                                    $genres = $listing_genres ? array_map(function($term) { return $term->name; }, $listing_genres) : [];
                                    echo get_template_part('template-parts/account/listing', '', [
                                        'post_id' => get_the_ID(),
                                        'name' => get_post_meta(get_the_ID(), 'name', true),
                                        'genres' => $genres,
                                        'thumbnail_url' => $thumbnail_url ? $thumbnail_url : get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp',
                                    ]);
                                } ?>

                                <div class="py-12 text-center">
                                    <a href="/listing-form"><button type="button" data-trigger="quote" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Create New Listing</button></a>
                                </div>

                            <?php }

                        } else { ?>


                            <!-- Logged in no listings state -->
                            <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                                <div class="pb-32 relative z-10">
                                    <span class="text-18 sm:text-22 block text-center mb-4">You don't have any listings yet</span>
                                    <a href="/listing-form"><button type="button" data-trigger="quote" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Create your first</button></a>
                                </div>

                                <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                                <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                            </div>

                        <?php }

                    } ?>


                </div>
            </div>
        </div>
</div>

<?php
get_footer();

