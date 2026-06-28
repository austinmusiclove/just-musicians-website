<?php
/**
 * The template for the applications page
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

                <div class="mb-6 md:mb-14 flex justify-between items-center flex-row">
                    <a href="<?php echo site_url('/applications/'); ?>"><h1 class="font-bold text-25">Applications</h1></a>
                    <?php if (is_user_logged_in()) { ?>
                        <a href="<?php echo site_url('/application-form/'); ?>" class="font-bold text-12 pt-1.5 pb-1 px-1.5 rounded bg-white border border-black/20 hover:drop-shadow cursor-pointer inline-block">Add +</a>
                    <?php } ?>
                </div>

                <?php if (!is_user_logged_in()) { ?>

                    <?php echo get_template_part('template-parts/global/empty-states/sign-in-to-access', '', [ 'message' => 'see your applications' ]); ?>

                <?php } else { ?>

                    <form id="applications-form"
                        hx-get="<?php echo site_url('/wp-html/v1/applications/'); ?>"
                        hx-target="#results"
                        hx-indicator="#applications-spinner"
                        hx-trigger="load"
                    ></form>

                    <span id="results"></span>

                    <div id="applications-spinner" class="my-8 flex items-center justify-center htmx-indicator">
                        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '8', 'color' => 'yellow']); ?>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
</div>

<?php
get_footer();
