<?php
/**
 * The template for the my gigs page
 *
 * @package JustMusicians
 */

get_header();

?>

<div id="page" class="flex flex-col grow">

    <div id="content" class="grow flex flex-col relative">
        <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
            <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-16 lg:top-20 h-fit">
                  <?php echo get_template_part('template-parts/account/sidebar', '', [ 'collapsible' => false ]); ?>
                </div>
            </div>
            <div class="col md:col-span-6 py-6 md:py-12">

                <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                    <a href="<?php echo site_url('/my-gigs/'); ?>"><h1 class="font-bold text-25">My Gigs</h1></a>
                </div>

                <?php if (!is_user_logged_in()) { ?>

                    <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to see your gigs';"></span>

                    <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                        <div class="pb-32 relative z-10">
                            <span class="text-18 sm:text-22 block text-center mb-4">Log in to see your gigs</span>
                            <button x-on:click="showLoginModal = true;" type="button" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Log In</button>
                        </div>

                        <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                        <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                    </div>

                <?php } else { ?>
                    <div
                        hx-get="<?php echo site_url('/wp-html/v1/my-gigs/'); ?>"
                        hx-target="#results"
                        hx-indicator=".spinner-start"
                        hx-trigger="load"
                    >
                        <span class="spinner-start htmx-indicator-block">
                            <?php
                            echo get_template_part('template-parts/listings/standard-listing-skeleton');
                            echo get_template_part('template-parts/listings/standard-listing-skeleton');
                            echo get_template_part('template-parts/listings/standard-listing-skeleton');
                            ?>
                        </span>
                        <span id="results" class="spinner-start htmx-indicator-block-replace"></span>
                        <span id="spinner-end" class="htmx-indicator-block">
                            <?php
                            echo get_template_part('template-parts/listings/standard-listing-skeleton');
                            echo get_template_part('template-parts/listings/standard-listing-skeleton');
                            echo get_template_part('template-parts/listings/standard-listing-skeleton');
                            ?>
                            <div class="my-8 flex items-center justify-center">
                                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                            </div>
                        </span>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

<?php
get_footer();
