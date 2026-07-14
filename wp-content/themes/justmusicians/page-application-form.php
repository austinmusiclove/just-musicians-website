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

                <div class="mb-6 md:mb-14 flex flex-col gap-4">
                    <h1 class="font-bold text-25">Create an Application</h1>
                    <p class="text-16 mb-4">Applications allow musicians to show interest in your gigs without having to deal with a mess of emails. Once they apply, you can review them in one convenient place.</p>
                </div>

                <?php if (!is_user_logged_in()) { ?>

                    <?php echo get_template_part('template-parts/global/empty-states/sign-in-to-access', '', [ 'message' => 'create an application' ]); ?>

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
                            <input type="text" id="title" name="title" x-model="title" placeholder="Live Band Application for Saturday Nights at Buck's" />
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block font-bold text-16 mb-2">Description</label>
                            <textarea id="description" class="w-full" name="description" x-model="description" placeholder="Tell the musicians a little bit more about the type of gigs they are applying for" rows="6"></textarea>
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
