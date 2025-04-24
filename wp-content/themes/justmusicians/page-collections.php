<?php
/**
 * The template for the collections landing page
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
                        <h1 class="font-bold text-22 sm:text-25">My Collections</h1>
                    </div>


                    <!------------ Delete Toasts ----------------->
                    <div class="h-4" x-on:remove-listing-card="$refs[$event.detail.post_id].style.display = 'none'" >
                        <?php echo get_template_part('template-parts/global/toasts/error-toast', '', ['event_name' => 'delete-error-toast']); ?>
                        <?php echo get_template_part('template-parts/global/toasts/success-toast', '', ['event_name' => 'delete-success-toast']); ?>
                        <div id="result"></div>
                    </div>


                    <!-- Logged out -->
                    <?php if (!is_user_logged_in()) { ?>

                        <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to see your collections';"></span>

                        <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                            <div class="pb-32 relative z-10">
                                <span class="text-18 sm:text-22 block text-center mb-4">Log in to see your collections</span>
                                <button x-on:click="showLoginModal = true;" type="button" data-trigger="quote" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Log In</button>
                            </div>

                            <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                            <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                        </div>


                    <!-- Logged in -->
                    <?php } else {

                        $current_user_id = get_current_user_id();


                        // Show Favorites collection

                        // Get thumbnail for favorites
                        $thumbnail_url = null;
                        echo get_template_part('template-parts/account/collection-listing', '', [
                            'post_id' => '',
                            'name' => 'Favorites',
                            'thumbnail_url' => $thumbnail_url ? $thumbnail_url : get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp',
                            'allow_delete' => false,
                        ]);


                        // Show User Collections
                        $collections = get_user_meta($current_user_id, 'collections', true);
                        if ( $collections and count($collections) > 0 ) {

                            // Query the posts
                            $args = [
                                'post_type'      => 'collection',
                                'post__in'       => $collections,
                                'post_status'    => 'publish',
                                'orderby'        => 'post__in',
                                'posts_per_page' => -1
                            ];
                            $query = new WP_Query($args);
                            if ($query->have_posts()) { ?>

                                <!-- Display user's collections -->

                                <?php while ($query->have_posts()) {
                                    $query->the_post();
                                    $thumbnail_url = null; //get_the_post_thumbnail_url(get_the_ID(), 'standard-listing');
                                    echo get_template_part('template-parts/account/collection-listing', '', [
                                        'post_id' => get_the_ID(),
                                        'name' => get_post_meta(get_the_ID(), 'name', true),
                                        'thumbnail_url' => $thumbnail_url ? $thumbnail_url : get_template_directory_uri() . '/lib/images/placeholder/placeholder-image.webp',
                                        'allow_delete' => true,
                                    ]);
                                }
                            }
                        }
                    } ?>


                </div>
            </div>
        </div>
</div>

<?php
get_footer();


