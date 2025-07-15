<?php
/**
 * The template for the inquiries landing page
 *
 * @package JustMusicians
 */

get_header();

// Get user inquiries
$inquiries_result = get_user_inquiries([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$inquiries_map = array_column($inquiries_result['inquiries'], null, 'post_id');

?>

<div id="page" class="flex flex-col grow"
    x-on:add-inquiry="redirect()"
>

    <div id="content" class="grow flex flex-col relative">
        <div class="container md:grid md:grid-cols-9 xl:grid-cols-12 gap-8 lg:gap-12">
            <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-16 lg:top-20 h-fit">
                  <?php echo get_template_part('template-parts/account/sidebar', '', []); ?>
                </div>
            </div>
            <div class="col md:col-span-6 py-6 md:py-12">

                <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                    <a href="/inquiries"><h1 class="font-bold text-22 sm:text-25">Inquiries</h1></a>
                </div>


                <!-- Logged out -->
                <?php if (!is_user_logged_in()) { ?>

                    <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to see your inquiries';"></span>

                    <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                        <div class="pb-32 relative z-10">
                            <span class="text-18 sm:text-22 block text-center mb-4">Log in to see your inquiries</span>
                            <button x-on:click="showLoginModal = true;" type="button" data-trigger="quote" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Log In</button>
                        </div>

                        <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                        <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                    </div>


                <!-- Logged in -->
                <?php } else { ?>

                    <!-- Show Inquiries -->
                    <div class="lg:block md:col-span-6 pb-4">
                        <div class="sticky top-36" x-data="{
                            showIncoming: true,
                            showOutgoing: false,
                            hideTabs() {
                                this.showIncoming = false;
                                this.showOutgoing = false;
                            },
                        }">

                            <!-- Preview tabs -->
                            <div class="flex items-start justify-between mb-6 border-b border-black/20">
                                <div class="flex gap-6 items-start">
                                    <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showIncoming}" x-on:click="hideTabs(); showIncoming = true;">Incoming</div>
                                    <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showOutgoing}" x-on:click="hideTabs(); showOutgoing = true;">Outgoing</div>
                                </div>
                            </div>


                            <!-- Full page preview -->
                            <div x-show="showIncoming" x-cloak>
                                <div
                                    hx-get="/wp-html/v1/inquiries/requests/"
                                    hx-trigger="load"
                                    hx-target="#incoming-results"
                                    hx-indicator="#incoming-spinner"
                                ></div>

                                <span id="incoming-results">
                                    <?php
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                    ?>
                                </span>

                                <div id="incoming-spinner" class="my-8 inset-0 flex items-center justify-center htmx-indicator">
                                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                                </div>
                            </div>

                            <!-- Search result preview -->
                            <div x-show="showOutgoing" x-cloak>
                                <div
                                    hx-get="/wp-html/v1/inquiries/"
                                    hx-trigger="load"
                                    hx-target="#outgoing-results"
                                    hx-indicator="#outgoing-spinner"
                                ></div>

                                <span id="outgoing-results">
                                    <?php
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                        echo get_template_part('template-parts/search/standard-listing-skeleton');
                                    ?>
                                </span>

                                <div id="outgoing-spinner" class="my-8 inset-0 flex items-center justify-center htmx-indicator">
                                    <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                                </div>
                            </div>

                        </div>
                    </div>



                <?php } ?>


            </div>
        </div>
    </div>

</div>

<?php
get_footer();


