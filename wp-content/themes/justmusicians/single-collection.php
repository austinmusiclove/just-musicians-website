<?php
/**
 * The template for the collections landing page
 *
 * @package JustMusicians
 */

get_header();

$is_favorites = get_query_var('wp-html-v1') == 'favorites';
$listings = [];
$collection_id = 0;

if ($is_favorites) {
    $listings = get_user_meta(get_current_user_id(), 'favorites', true);
} else {
    $listings = get_field('listings');
    $collection_id = get_the_ID();
}

// Get user collections
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');

?>

<div id="page" class="flex flex-col grow">

        <input type="hidden" name="search" value="" x-bind:value="searchInput" x-init="$watch('searchInput', value => { searchVal = value; $dispatch('filterupdate'); })" />
        <div id="content" class="grow flex flex-col relative">
            <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
                <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                    <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-16 lg:top-20 h-fit">
                      <?php echo get_template_part('template-parts/account/sidebar', '', [
                        'collapsible' => false
                      ]); ?>
                    </div>
                </div>
                <div class="col md:col-span-6 py-6 md:py-12"
                    x-data="{
                        collectionsMap: <?php echo clean_arr_for_doublequotes($collections_map); ?>,
                        get sortedCollections()                              { return getSortedCollections(this, <?php echo $collection_id; ?>); },
                        _showEmptyFavoriteButton(listingId)                  { return showEmptyFavoriteButton(this, listingId); },
                        _showFilledFavoriteButton(listingId)                 { return showFilledFavoriteButton(this, listingId); },
                        _showEmptyCollectionButton(collectionId, listingId)  { return showEmptyCollectionButton(this, collectionId, listingId); },
                        _showFilledCollectionButton(collectionId, listingId) { return showFilledCollectionButton(this, collectionId, listingId); },

                        players: {},
                        playersMuted: true,
                        playersPaused: false,
                        _initPlayer(playerId, videoData) { initPlayer(this, playerId, videoData); },
                        _pauseAllPlayers()               { pauseAllPlayers(this); },
                        _pausePlayer(playerId)           { pausePlayer(this, playerId); },
                        _playPlayer(playerId)            { playPlayer(this, playerId); },
                        _toggleMute()                    { toggleMute(this); },
                        _setupVisibilityListener()       { setupVisibilityListener(this); },
                    }"
                    x-on:init-youtube-player="_initPlayer($event.detail.playerId, $event.detail.videoData);"
                    x-on:pause-all-youtube-players="_pauseAllPlayers()"
                    x-on:pause-youtube-player="_pausePlayer($event.detail.playerId)"
                    x-on:play-youtube-player="_playPlayer($event.detail.playerId)"
                    x-on:mute-youtube-players="_toggleMute()"
                    x-init="_setupVisibilityListener()"
                >

                    <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                        <a href="/collections"><h1 class="font-bold text-22 sm:text-25">My Collections</h1></a>
                    </div>

                    <div class="mb-2 md:mb-2 flex justify-start items-center flex-row gap-2">
                        <h2 class="font-bold text-18 sm:text-25"><?php if ($is_favorites) { echo 'Favorites'; } else { the_title(); } ?></h2>
                        <div class="flex items-center gap-2">
                            <div class="h-5 w-px bg-black/20"></div>
                            <span x-text="collectionsMap['<?php echo $collection_id; ?>'].listings.length + ' ' + (collectionsMap['<?php echo $collection_id; ?>'].listings.length == 1 ? 'Listing' : 'Listings')"></span>
                        </div>
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
                    <?php } else { ?>

                        <form id="hx-form"
                            hx-get="<?php echo site_url('/wp-html/v1/listings-by-id'); ?>"
                            hx-trigger="load"
                            hx-target="#results"
                            hx-indicator="#spinner"
                        >

                            <input type="hidden" name="listing_ids" value="<?php echo implode(',', $listings); ?>" />
                            <input type="hidden" name="collection_id" value="<?php echo $collection_id; ?>" />

                            <span id="results">
                                <?php
                                    echo get_template_part('template-parts/listings/standard-listing-skeleton');
                                    echo get_template_part('template-parts/listings/standard-listing-skeleton');
                                    echo get_template_part('template-parts/listings/standard-listing-skeleton');
                                    echo get_template_part('template-parts/listings/standard-listing-skeleton');
                                    echo get_template_part('template-parts/listings/standard-listing-skeleton');
                                ?>
                            </span>

                            <div id="spinner" class="my-8 inset-0 flex items-center justify-center htmx-indicator">
                                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                            </div>

                        </form>

                    <?php } ?>

                </div>
            </div>
        </div>
</div>

<?php
get_footer();



