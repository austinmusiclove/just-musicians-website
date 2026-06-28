<?php
/**
 * The template for the event form page
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

                <a href="<?php echo site_url('/my-events/'); ?>" class="inline-flex items-center gap-1 text-14 text-black/60 hover:text-black mb-8 sm:mb-16">
                    <span>←</span>
                    <span>Back to My Events</span>
                </a>

                <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                    <h1 class="font-bold text-25">Create Event</h1>
                </div>

                <?php if (!is_user_logged_in()) { ?>

                    <?php echo get_template_part('template-parts/global/empty-states/sign-in-to-access', '', [ 'message' => 'create an event' ]); ?>

                <?php } else { ?>

                    <form
                        x-data="{
                            eventName: '',
                            startDate: '',
                            startTime: '',
                            endTime: '',
                            addressLine1: '',
                            addressLine2: '',
                            city: '',
                            state: '',
                            zipCode: '',
                            lat: '',
                            lng: '',
                            genres: [],
                            ensembleSize: [],
                            details: '',
                            budget: '',
                            compensation: '',
                            requestQuote: false,
                            requestDraw: false,
                        }"
                        hx-post="<?php echo site_url('/wp-html/v1/events/'); ?>"
                        hx-target="#create-event-result"
                        hx-indicator="#submit-button-content"
                    >
                        <?php echo get_template_part('template-parts/events/event-form/event-name-input'); ?>
                        <?php echo get_template_part('template-parts/events/event-form/date-time-inputs'); ?>
                        <?php echo get_template_part('template-parts/events/event-form/location-inputs'); ?>
                        <?php echo get_template_part('template-parts/events/event-form/taxonomy-inputs'); ?>
                        <?php echo get_template_part('template-parts/events/event-form/details-input'); ?>
                        <?php echo get_template_part('template-parts/events/event-form/compensation-inputs'); ?>

                        <div class="flex gap-2 mt-8">
                            <button type="submit" x-bind:disabled="genres.length < 1" class="bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 w-fit disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="submit-button-content">
                                    <span class="htmx-indicator-component-block-replace">Create Event</span>
                                    <span class="htmx-indicator-component-block mx-2 my-1">
                                        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
                                    </span>
                                </span>
                            </button>
                        </div>
                    </form>

                    <span id="create-event-result"></span>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<?php
get_footer();
