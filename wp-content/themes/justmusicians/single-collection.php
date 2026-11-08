<?php
/**
 * The template for the collections landing page
 *
 * @package JustMusicians
 */

// Authorize
if (!is_user_logged_in()) { wp_safe_redirect(site_url()); exit; }

get_header();

$is_favorites  = get_query_var('wp-html-v1') == 'favorites';
$collection_id = $is_favorites ? 0 : get_the_ID();

// Only users with at least view access can open a collection
if (!$is_favorites && is_wp_error(user_can_view_collection($collection_id))) {
    wp_safe_redirect(site_url());
    exit;
}

// Get user collections
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');

?>

<div id="page" class="flex flex-col grow">

        <div id="content" class="grow flex flex-col relative">
            <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
                <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                    <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-16 lg:top-20 h-fit">
                      <?php echo get_template_part('template-parts/account/sidebar', '', [ 'collapsible' => false ]); ?>
                    </div>
                </div>
                <div class="col md:col-span-6 py-8 md:py-12" x-data="{
                        collectionsMap: <?php echo clean_arr_for_doublequotes($collections_map); ?>,
                        reorderMode: false,
                    }"
                >

                    <!-- Back button -->
                    <span class="block sm:hidden py-2 opacity-50 cursor-pointer">
                        <a class="flex hover:underline" href="<?php echo site_url('/collections/'); ?>">
                            <img class="ml-[-8px] h-6 opacity-80 text-grey" src="<?php echo get_template_directory_uri() . '/lib/images/icons/chevron-left.svg'; ?>" alt="Back to Collections" />
                            <span class="text-18" >Back to Collections</span>
                        </a>
                    </span>

                    <div class="mb-2 md:mb-2 flex justify-start items-center flex-row gap-2">
                        <h2 class="font-bold text-25"><?php if ($is_favorites) { echo 'Favorites'; } else { the_title(); } ?></h2>
                        <div class="flex items-center gap-2">
                            <div class="h-5 w-px bg-black/20"></div>
                            <span x-text="collectionsMap['<?php echo $collection_id; ?>'].listings.length + ' ' + (collectionsMap['<?php echo $collection_id; ?>'].listings.length == 1 ? 'Listing' : 'Listings')"></span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <?php if (!$is_favorites && !is_wp_error(user_owns_collection($collection_id))) { ?>
                            <?php echo get_template_part('template-parts/access/share-button', '', [
                                'label'        => 'Share',
                                'heading'      => 'Share Collection',
                                'subject_id'   => $collection_id,
                                'subject_type' => 'collection',
                                'permalink'    => get_the_permalink(),
                            ]); ?>
                        <?php } ?>
                        <?php if (!is_wp_error(user_can_edit_collection($collection_id))) { ?>
                        <button type="button" data-reorder-toggle x-on:click="reorderMode = !reorderMode"
                            class="hidden sm:inline-block hover:bg-yellow border border-black/20 px-3 py-2 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap"
                            :class="reorderMode ? 'bg-navy text-white' : 'bg-white text-black'"
                            x-text="reorderMode ? 'Done Reordering' : 'Reorder'"></button>
                        <?php } ?>

                        <div class="spinner-start htmx-indicator flex items-center justify-center">
                            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
                        </div>
                    </div>



                    <!-- Logged out -->
                    <?php if (!is_user_logged_in()) { ?>

                        <?php echo get_template_part('template-parts/global/empty-states/sign-in-to-access', '', [ 'message' => 'see your collections' ]); ?>


                    <!-- Logged in -->
                    <?php } else { ?>

                        <form id="hx-form"
                            hx-get="/wp-html/v1/collections/<?php echo $collection_id; ?>/listings/"
                            hx-trigger="load"
                            hx-target="#results"
                            hx-indicator=".spinner-start"
                        >

                            <span class="spinner-start htmx-indicator-block">
                                <?php
                                echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                                echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                                echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                                echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                                echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                                ?>
                            </span>

                            <!-- Results container -->
                            <?php get_template_part('template-parts/cards/card-containers/listing-cards-container', '', [
                                'id'              => 'results',
                                'reorderable'     => true,
                                'collection_id'   => $collection_id,
                            ]); ?>

                            <span class="page-indicator htmx-indicator-block">
                                <?php
                                echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                                echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                                echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                                echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                                echo get_template_part('template-parts/cards/card-placeholders/standard-listing-card-skeleton');
                                ?>
                            </span>
                        </form>

                    <?php } ?>

                </div>
            </div>
        </div>
</div>

<?php
get_footer();



