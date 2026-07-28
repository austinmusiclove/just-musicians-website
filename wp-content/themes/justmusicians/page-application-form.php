<?php
/**
 * The template for the application form page
 *
 * @package JustMusicians
 */

$user_events_result = get_user_events([
    'start_date_after' => date('Y-m-d'),
    'nopaging'         => true,
]);
$upcoming_events = $user_events_result['events'];

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
                </div>

                <?php if (!is_user_logged_in()) { ?>

                    <?php echo get_template_part('template-parts/global/empty-states/sign-in-to-access', '', [ 'message' => 'create an application' ]); ?>

                <?php } else { ?>

                    <form class="flex flex-col gap-8"
                        x-data="{
                            title: '',
                        }"
                        hx-post="<?php echo site_url('/wp-html/v1/applications/'); ?>"
                        hx-target="#create-application-result"
                        hx-indicator="#submit-button-content"
                    >
                        <div class="flex flex-col gap-2">
                            <label for="title" class="block font-bold text-16">Title</label>
                            <input type="text" id="title" name="title" x-model="title" placeholder="Live Band Application for Saturday Nights at Buck's" />
                        </div>

                        <!-- Description -->
                        <div class="flex flex-col gap-2">
                            <label for="description" class="block font-bold text-16">Description</label>
                            <?php wp_editor('', 'description', [
                                'textarea_name' => 'description',
                                'textarea_rows' => 8,
                                'media_buttons' => false,
                                'teeny'         => true,
                                'quicktags'     => false,
                                'toolbar1'      => 'bold,italic,underline,bullist,numlist,link,unlink',
                                'toolbar2'      => '',
                            ]); ?>
                        </div>

                        <!-- Events -->
                        <?php if (!empty($upcoming_events)) { ?>
                        <div class="flex flex-col gap-2">
                            <label class="block font-bold text-16">Events</label>
                            <p class="text-16">By associating events with your applciation, musicians can submit their availability for each event when filling out the application.</p>
                            <div class="flex flex-col gap-2">
                                <?php foreach ($upcoming_events as $event) { ?>
                                <label class="flex items-center gap-3 cursor-pointer p-2 border border-black/20 rounded-sm hover:bg-yellow-light">
                                    <input type="checkbox" name="events[]" value="<?php echo $event['post_id']; ?>">
                                    <div class="flex flex-col">
                                        <span class="text-14 font-semibold"><?php echo esc_html($event['event_name']); ?></span>
                                        <span class="text-12 text-black/60"><?php echo $event['start_date'] ? gmdate('M j, Y', strtotime($event['start_date'])) : ''; ?></span>
                                    </div>
                                </label>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>

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
