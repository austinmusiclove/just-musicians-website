<?php
/**
 * Template for individual event view
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
                    <h1 class="font-bold text-25"><?php the_title(); ?></h1>
                </div>

                <div x-data="{
                    showEventDetails: true,
                    showApplicants: false,
                    hideTabs() {
                        this.showEventDetails = false;
                        this.showApplicants = false;
                    },
                }">
                    <!-- Tabs -->
                    <div class="flex items-start justify-between border-b border-black/20">
                        <div class="flex gap-6 items-start">
                            <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showEventDetails}" x-on:click="hideTabs(); showEventDetails = true;">Event Details</div>
                            <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showApplicants}" x-on:click="hideTabs(); showApplicants = true;">Applicants</div>
                        </div>
                    </div>

                    <?php echo get_template_part('template-parts/events/event-details'); ?>
                    <?php echo get_template_part('template-parts/events/event-applicants'); ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
get_footer();
