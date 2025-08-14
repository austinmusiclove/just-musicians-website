<?php
/**
 * The template for the collections landing page
 *
 * @package JustMusicians
 */

get_header();

?>

<div id="page" class="flex flex-col grow" x-data="{
        showAddCollectionPopup: false,
    }"
    x-on:add-collection="redirect('<?php echo $_SERVER['REQUEST_URI']; ?>')"
>

        <input type="hidden" name="search" value="" x-bind:value="searchInput" x-init="$watch('searchInput', value => { searchVal = value; $dispatch('filterupdate'); })" />
        <div id="content" class="grow flex flex-col relative">
            <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
                <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                    <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-16 lg:top-20 h-fit">
                      <?php echo get_template_part('template-parts/account/sidebar', '', [ 'collapsible' => false ]); ?>
                    </div>
                </div>
                <div class="col md:col-span-6 py-6 md:py-12">

                    <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                        <a href="/collections"><h1 class="font-bold text-22 sm:text-25">Collections</h1></a>
                        <?php if (is_user_logged_in()) { ?>
                            <button class="font-bold text-12 pt-1.5 pb-1 px-1.5 rounded bg-white border border-black/20 hover:drop-shadow cursor-pointer" x-on:click="showAddCollectionPopup = true">Add +</button>
                        <?php } ?>
                    </div>


                     <!-- Add collection popup -->
                    <div class="popup-wrapper px-2 pt-28 md:pt-0 w-screen h-screen  fixed top-0 left-0 z-50 flex items-center justify-center" x-show="showAddCollectionPopup" x-cloak>
                        <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer" x-on:click="showAddCollectionPopup = false" ></div>
                        <div class="bg-white relative w-auto h-auto gap-4 shadow-black-offset flex flex-col items-stretch justify-center pb-6 px-6 pt-4" style="max-width: 780px;">

                            <div>

                                <div class="w-full flex items-center justify-between mb-8">
                                    <h4 class="font-bold text-25 w-full">Create a new collection</h4>
                                    <img class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" x-on:click="showAddCollectionPopup = false"/>
                                </div>

                                <form class="w-full flex items-stretch gap-2 mb-4"
                                    hx-post="<?php echo site_url('/wp-html/v1/collections/'); ?>"
                                    hx-target="#create-collection-result"
                                >
                                    <input type="hidden" name="error_event" value="error-toast-add-collection">
                                    <input type="hidden" name="success_event" value="success-toast-add-collection">
                                    <input class="w-full" placeholder="Collection name" type="text" name="collection_name" required>
                                    <button type="submit" class="shrink-0 rounded text-14 bg-yellow hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-4 hover:border-black disabled:bg-grey disabled:text-white">Create</button>
                                    <span id="create-collection-result"></span>
                                </form>
                                <!-- Toasts -->
                                <div class="min-h-10 max-w-[35rem] sm:min-w-[35rem]">
                                    <?php
                                    echo get_template_part('template-parts/global/toasts/success-toast', '', ['customEvent' => 'success-toast-add-collection']);
                                    echo get_template_part('template-parts/global/toasts/error-toast',   '', ['customEvent' => 'error-toast-add-collection']);
                                    ?>
                                </div>

                            </div>
                        </div>
                    </div>


                    <!------------ Hide Deleted ----------------->
                    <div class="h-4" x-on:remove-listing-card="$refs[$event.detail.post_id].style.display = 'none'" ></div>


                    <!-- Logged out -->
                    <?php if (!is_user_logged_in()) { ?>

                        <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to see your collections';"></span>

                        <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                            <div class="pb-32 relative z-10">
                                <span class="text-18 sm:text-22 block text-center mb-4">Log in to see your collections</span>
                                <button x-on:click="showLoginModal = true;" type="button" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Log In</button>
                            </div>

                            <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                            <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                        </div>


                    <!-- Logged in -->
                    <?php } else { ?>

                        <div
                            hx-get="<?php echo site_url('/wp-html/v1/collections'); ?>"
                            hx-trigger="load"
                            hx-target="#results"
                            hx-indicator="#spinner"
                        ></div>

                        <span id="results">
                            <?php
                                echo get_template_part('template-parts/listings/standard-listing-skeleton');
                                echo get_template_part('template-parts/listings/standard-listing-skeleton');
                                echo get_template_part('template-parts/listings/standard-listing-skeleton');
                                echo get_template_part('template-parts/listings/standard-listing-skeleton');
                                echo get_template_part('template-parts/listings/standard-listing-skeleton');
                            ?>
                        </span>

                        <div id="spinner" class="my-8 flex items-center justify-center htmx-indicator">
                            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                        </div>


                    <?php } ?>


                </div>
            </div>
        </div>
</div>

<?php
get_footer();


