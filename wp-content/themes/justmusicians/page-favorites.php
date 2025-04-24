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
                <div class="col md:col-span-6 py-6 md:py-12"
                    x-data='{
                        players: {},
                        playersMuted: true,
                        playersPaused: false,
                        _initPlayer(playerId, videoId) { initPlayer(this, playerId, videoId); },
                        _pauseAllPlayers()             { pauseAllPlayers(this); },
                        _pausePlayer(playerId)         { pausePlayer(this, playerId); },
                        _playPlayer(playerId)          { playPlayer(this, playerId); },
                        _toggleMute()                  { toggleMute(this); },
                        _setupVisibilityListener()     { setupVisibilityListener(this); },
                    }'
                    x-on:init-youtube-player="_initPlayer($event.detail.playerId, $event.detail.videoId);"
                    x-on:pause-all-youtube-players="_pauseAllPlayers()"
                    x-on:pause-youtube-player="_pausePlayer($event.detail.playerId)"
                    x-on:play-youtube-player="_playPlayer($event.detail.playerId)"
                    x-on:mute-youtube-players="_toggleMute()"
                    x-init="_setupVisibilityListener()"
                >

                    <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                        <h1 class="font-bold text-22 sm:text-25">My Collections</h1>
                    </div>

                    <div class="mb-2 md:mb-2 flex justify-start items-center flex-row gap-2">
                        <h2 class="font-bold text-18 sm:text-25">Favorites</h2>
                        <div class="flex items-center gap-2">
                            <div class="h-5 w-px bg-black/20"></div>
                            <span id="max_num_results" hx-swap-oob="outerHTML"></span>
                        </div>
                    </div>


                    <!------------ Delete Toasts ----------------->
                    <div class="h-4" x-on:remove-listing-card="$refs[$event.detail.post_id].style.display = 'none'" >
                        <?php echo get_template_part('template-parts/global/toasts/error-toast', '', ['event_name' => 'delete-error-toast']); ?>
                        <?php echo get_template_part('template-parts/global/toasts/success-toast', '', ['event_name' => 'delete-success-toast']); ?>
                        <div id="result"></div>
                    </div>


                    <!-- Logged out -->
                    <?php if (!is_user_logged_in()) { ?>

                        <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to see your favorites';"></span>

                        <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                            <div class="pb-32 relative z-10">
                                <span class="text-18 sm:text-22 block text-center mb-4">Log in to see your favorites</span>
                                <button x-on:click="showLoginModal = true;" type="button" data-trigger="quote" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Log In</button>
                            </div>

                            <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                            <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                        </div>


                    <!-- Logged in -->
                    <?php } else {

                        $current_user_id = get_current_user_id();

                        // Show listings
                        $favorites = get_user_meta($current_user_id, 'favorites', true);
                        if ( $favorites and count($favorites) > 0 ) {

                            // Query the posts
                            $args = [
                                'post_type'      => 'listing',
                                'post__in'       => $favorites,
                                'post_status'    => 'publish',
                                'orderby'        => 'post__in',
                                'posts_per_page' => -1
                            ];
                            $query = new WP_Query($args);
                            if ($query->have_posts()) { ?>

                                <!-- Display user's collections -->

                                <?php while ($query->have_posts()) {
                                    $query->the_post();
                                    $genres = get_the_terms(get_the_ID(), 'genre');
                                    get_template_part('template-parts/search/standard-listing', '', [
                                        'name' => get_field('name'),
                                        'location' => get_field('city') . ', ' . get_field('state'),
                                        'description' => get_field('description'),
                                        'genres' => $genres ? array_map(fn($term) => $term->name, $genres) : [],
                                        'thumbnail_url' => get_the_post_thumbnail_url(get_the_ID(), 'standard-listing'),
                                        'website' => get_field('website'),
                                        'facebook_url' => get_field('facebook_url'),
                                        'instagram_url' => get_field('instagram_url'),
                                        'x_url' => get_field('x_url'),
                                        'youtube_url' => get_field('youtube_url'),
                                        'tiktok_url' => get_field('tiktok_url'),
                                        'bandcamp_url' => get_field('bandcamp_url'),
                                        'spotify_artist_url' => get_field('spotify_artist_url'),
                                        'apple_music_artist_url' => get_field('apple_music_artist_url'),
                                        'soundcloud_url' => get_field('soundcloud_url'),
                                        'youtube_video_urls' => get_field('youtube_video_urls'),
                                        'youtube_video_ids' => get_youtube_video_ids(get_field('youtube_video_urls')),
                                        'verified' => get_field('verified'),
                                        'lazyload_thumbnail' => false,
                                        'last' => false,
                                        'is_last_page' => true,
                                    ]);
                                }
                            } else { ?>

                                <!-- No favorites state -->
                                <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                                    <div class="pb-32 relative z-10">
                                        <span class="text-18 sm:text-22 block text-center mb-4">You don't have any favorites yet</span>
                                    </div>

                                    <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                                    <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                                </div>


                            <?php }


                        } else { ?>

                            <!-- No favorites state -->
                            <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                                <div class="pb-32 relative z-10">
                                    <span class="text-18 sm:text-22 block text-center mb-4">You don't have any favorites yet</span>
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


