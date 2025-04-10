<?php
/**
 * The template for the listings landing page
 *
 * @package JustMusicians
 */

get_header();

?>

<!--
<header class="bg-yellow-light pt-12 md:pt-24 pb-8 md:pb-16 relative overflow-hidden">
    <div class="container grid grid-cols-1 sm:grid-cols-7 gap-x-8 md:gap-x-24 gap-y-10 relative">
        <h1 class="font-bold text-32 md:text-36 lg:text-40"><?php the_title(); ?></h1>
    </div>
</header>
-->


<div id="page" class="flex flex-col grow">


        <!-- handle listing invitiation -->
        <?php if (is_user_logged_in()) {
            if (isset($_GET['lic'])) {
                // send listing invitation validation with redirect without the param in url to avoid infinite loop
                $success = add_listing_by_invitation_code($_GET['lic']);
                // if success, redirect back to the same page but remove query params to avoid an infinite loop
                if ($success and !is_wp_error($success)) { ?>
                    <div x-init="redirect('<?php echo strtok($_SERVER['REQUEST_URI'], '?'); ?>')"></div>
                <?php } else { ?>
                    <p>Failed to add listing from invitation link with error: <span class="text-yellow"><?php echo $success->get_error_message(); ?></span></p>
                <?php }
            }
        } ?>
    
        <input type="hidden" name="search" value="" x-bind:value="searchInput" x-init="$watch('searchInput', value => { searchVal = value; $dispatch('filterupdate'); })" />
        <div id="content" class="grow flex flex-col relative">
            <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
                <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                    <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-16 lg:top-20 h-fit">
                      <?php echo get_template_part('template-parts/account/sidebar', '', array()); ?>
                    </div>
                </div>
                <div class="col md:col-span-6 py-6 md:py-12">

                    <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                        <h1 class="font-bold text-22 sm:text-25">My Listings</h1>
                        <button class="font-bold text-12 pt-1.5 pb-1 px-1.5 rounded bg-white border border-black/20 hover:drop-shadow cursor-pointer">Add +</button>
                    </div>

                    <?php 
                        $have_listings = true;
                        if ($have_listings) {
                    ?>

                    <div class="flex items-center justify-between md:justify-start">
                        <?php echo get_template_part('template-parts/search/sort', '', array(
                            'show_number' => false
                        )); ?>
                    </div>

                    <span id="results">
                        <?php echo get_template_part('template-parts/account/listing', '', array(
                            'name' => 'Chastity',
                            'genres' => array('indie rock', 'country'),
                            'thumbnail_url' => '/lib/images/placeholder/chastity.jpg'
                        )); ?>
                        <?php echo get_template_part('template-parts/account/listing', '', array(
                            'name' => 'Tribe Mafia',
                            'genres' => array('hip-hop', 'pop', 'soul'),
                            'thumbnail_url' => '/lib/images/placeholder/tribe-mafia.png'

                        )); ?>
                        <?php echo get_template_part('template-parts/account/listing', '', array(
                            'name' => 'Guitar Lessons',
                            'genres' => array('classical', 'country', 'rock'),
                            'thumbnail_url' => '/lib/images/placeholder/guitar-lessons.jpg'
                        )); ?>
                    </span>

                    <div class="py-12 text-center">
                        <button type="button" data-trigger="quote" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Create New Listing</button>
                    </div>

                    <?php } else { ?>
                        <!-- No listings state -->
                        <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                            <div class="pb-32 relative z-10">
                                <span class="text-18 sm:text-22 block text-center mb-4">You don't have any listings yet</span>
                                <button type="button" data-trigger="quote" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Create your first</button>
                            </div>

                            <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                            <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                        </div>

                    <?php } ?>


                </div>


            </div>
        </div>
</div>

<?php
get_footer();

