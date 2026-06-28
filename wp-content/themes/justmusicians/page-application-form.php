<?php
/**
 * The template for the application form page
 *
 * @package JustMusicians
 */

get_header();

?>

<div id="page" class="flex flex-col grow">

    <div id="content" class="grow flex flex-col relative">
        <div class="container md:grid md:grid-cols-9 gap-8 lg:gap-12">
            <div class="hidden md:col-span-3 border-r border-black/20 pr-8 md:flex flex-row">
                <div id="sticky-sidebar" class="sticky pt-24 pb-24 md:pb-12 w-full top-16 lg:top-20 h-fit">
                  <?php echo get_template_part('template-parts/account/sidebar', '', [ 'collapsible' => false ]); ?>
                </div>
            </div>
            <div class="col md:col-span-6 py-6 md:py-12">

                <a href="<?php echo site_url('/applications/'); ?>" class="inline-flex items-center gap-1 text-14 text-black/60 hover:text-black mb-8 sm:mb-16">
                    <span>←</span>
                    <span>Back to Applications</span>
                </a>

                <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                    <h1 class="font-bold text-25">Create Application</h1>
                </div>

                <?php if (!is_user_logged_in()) { ?>

                    <span x-init="showLoginModal = true; showSignupModal = false; loginModalMessage = 'Sign in to create an application';"></span>

                    <div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

                        <div class="pb-32 relative z-10">
                            <span class="text-18 sm:text-22 block text-center mb-4">Sign in to create an application</span>
                            <button x-on:click="showLoginModal = true;" type="button" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-12 px-2 py-2">Sign In</button>
                        </div>

                        <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
                        <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

                    </div>

                <?php } else { ?>

                    <form
                        x-data="{
                            title: '',
                            description: '',
                        }"
                        hx-post="<?php echo site_url('/wp-html/v1/applications/'); ?>"
                        hx-target="#create-application-result"
                        hx-indicator="#submit-button-content"
                    >
                        <div class="mb-4">
                            <label for="title" class="block font-bold text-16 mb-2">Title</label>
                            <input type="text" id="title" name="title" x-model="title" placeholder="Application title" />
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block font-bold text-16 mb-2">Description</label>
                            <textarea id="description" name="description" x-model="description" placeholder="Describe your application" rows="6"></textarea>
                        </div>

                        <div class="flex gap-2 mt-8">
                            <button type="submit" x-bind:disabled="!title" class="bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 w-fit disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="submit-button-content">
                                    <span class="htmx-indicator-component-block-replace">Create Application</span>
                                    <span class="htmx-indicator-component-block mx-2 my-1">
                                        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
                                    </span>
                                </span>
                            </button>
                        </div>
                    </form>

                    <span id="create-application-result"></span>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<?php
get_footer();
