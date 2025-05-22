<?php
/**
 * The template for the inquiries landing page
 *
 * @package JustMusicians
 */

get_header();

// Get inquiry listings invited
$listings_invited = get_post_meta(get_the_ID(), 'listings_invited', true);
$listings_invited = is_array($listings_invited) ? $listings_invited : [];
$num_listings = count($listings_invited);

// Get user collections
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');
// Get user inquiries
$inquiries_result = get_user_inquiries([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$inquiries_map = array_column($inquiries_result['inquiries'], null, 'post_id');

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
                    x-data="{
                        collectionsMap: <?php echo clean_arr_for_doublequotes($collections_map); ?>,
                        get sortedCollections()                              { return getSortedCollections(this, ''); },
                        _showEmptyFavoriteButton(listingId)                  { return showEmptyFavoriteButton(this, listingId); },
                        _showFilledFavoriteButton(listingId)                 { return showFilledFavoriteButton(this, listingId); },
                        _showEmptyCollectionButton(collectionId, listingId)  { return showEmptyCollectionButton(this, collectionId, listingId); },
                        _showFilledCollectionButton(collectionId, listingId) { return showFilledCollectionButton(this, collectionId, listingId); },
                        inquiriesMap: <?php echo clean_arr_for_doublequotes($inquiries_map); ?>,
                        get sortedInquiries()                                { return getSortedInquiries(this); },
                        _addInquiry(postId, subject, listings, permalink)    { return addInquiry(this, postId, subject, listings, permalink); },
                        _showAddListingToInquiryButton(inquiryId, listingId) { return showAddListingToInquiryButton(this, inquiryId, listingId); },
                        _showListingInInquiry(inquiryId, listingId)          { return showListingInInquiry(this, inquiryId, listingId); },
                        players: {},
                        playersMuted: true,
                        playersPaused: false,
                        _initPlayer(playerId, videoId) { initPlayer(this, playerId, videoId); },
                        _pauseAllPlayers()             { pauseAllPlayers(this); },
                        _pausePlayer(playerId)         { pausePlayer(this, playerId); },
                        _playPlayer(playerId)          { playPlayer(this, playerId); },
                        _toggleMute()                  { toggleMute(this); },
                        _setupVisibilityListener()     { setupVisibilityListener(this); },
                    }"
                    x-on:add-inquiry="_addInquiry($event.detail.post_id, $event.detail.subject, $event.detail.listings, $event.detail.permalink)"
                    x-on:init-youtube-player="_initPlayer($event.detail.playerId, $event.detail.videoId);"
                    x-on:pause-all-youtube-players="_pauseAllPlayers()"
                    x-on:pause-youtube-player="_pausePlayer($event.detail.playerId)"
                    x-on:play-youtube-player="_playPlayer($event.detail.playerId)"
                    x-on:mute-youtube-players="_toggleMute()"
                    x-init="_setupVisibilityListener()"
                >
                    <span id="inquiry-result"></span>

                    <!------------ Toasts ----------------->
                    <div class="h-4">
                        <?php echo get_template_part('template-parts/global/toasts/error-toast', '', []); ?>
                        <?php echo get_template_part('template-parts/global/toasts/success-toast', '', []); ?>
                        <div id="result"></div>
                    </div>


                    <!-- Logged out -->
                    <?php if (!is_user_logged_in()) { ?>

                        <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to see your inquiries';"></span>

                        <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                            <div class="pb-32 relative z-10">
                                <span class="text-18 sm:text-22 block text-center mb-4">Log in to see this page</span>
                                <button x-on:click="showLoginModal = true;" type="button" data-trigger="quote" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Log In</button>
                            </div>

                            <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                            <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                        </div>


                    <!-- Logged in -->
                    <?php } else if (is_wp_error(user_owns_inquiry(['inquiry_id' => get_the_ID()]))) { ?>

                        <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                            <a href="/inquiries"><h1 class="font-bold text-22 sm:text-25">My Inquiries</h1></a>
                        </div>

                        <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                            <div class="pb-32 relative z-10">
                                <span class="text-18 sm:text-22 block text-center mb-4">You are not authorized to see this page</span>
                            </div>

                            <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                            <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                        </div>

                    <?php } else { ?>

                        <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                            <a href="/inquiries"><h1 class="font-bold text-22 sm:text-25">My Inquiries</h1></a>
                        </div>

                        <div class="mb-2 md:mb-2 flex justify-start items-center flex-row gap-2">
                            <h2 class="font-bold text-18 sm:text-25"><?php the_title(); ?></h2>
                            <div class="flex items-center gap-2">
                                <div class="h-5 w-px bg-black/20"></div>
                                <?php $num_listings_label = $num_listings == 1 ? ' Quote Requested' : ' Quotes Requested'; ?>
                                <span><?php echo $num_listings . $num_listings_label; ?></span>
                            </div>
                        </div>


                        <?php echo get_template_part('template-parts/account/inquiry-detail', '', ['post_id' => get_the_ID()] ); ?>

                        <div id="hx-suggestions"
                            hx-get="/wp-html/v1/inquiries/<?php echo get_the_ID(); ?>/suggestions/"
                            hx-trigger="load"
                            hx-target="#suggestions-results"
                            hx-indicator="#suggestions-spinner"
                        >
                            <input type="hidden" name="listing_ids" value="<?php echo implode(',', $listings_invited); ?>" />

                            <h2 class="font-bold text-18 sm:text-25 my-2">Suggestions</h2>

                            <div class="block p-4 max-h-[26rem] overflow-y-auto">
                                <span id="suggestions-results">
                                    <?php
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                    ?>
                                </span>

                                <div id="suggestions-spinner" class="my-8 inset-0 flex items-center justify-center htmx-indicator">
                                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                                </div>
                            </div>

                        </div>

                        <form id="hx-listings"
                            hx-get="/wp-html/v1/inquiries/<?php echo get_the_ID(); ?>/listings/"
                            hx-trigger="load delay:200ms"
                            hx-target="#results"
                            hx-indicator="#spinner"
                        >
                            <input type="hidden" name="listing_ids" value="<?php echo implode(',', $listings_invited); ?>" />

                            <h2 class="font-bold text-18 sm:text-25 my-2">Listings who have received this inquiry</h2>

                            <div class="block my-4 p-4 max-h-[26rem] overflow-y-auto">
                                <span id="results">
                                    <?php
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                    ?>
                                </span>

                                <div id="spinner" class="my-8 inset-0 flex items-center justify-center htmx-indicator">
                                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                                </div>
                            </div>

                        </form>

                    <?php } ?>

                </div>
            </div>
        </div>
</div>

<?php
get_footer();



