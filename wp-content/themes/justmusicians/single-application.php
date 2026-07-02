<?php
/**
 * Template for individual application view
 *
 * @package JustMusicians
 */

// Authorize
$auth = user_can_view_single_application(get_the_ID());
if (is_wp_error($auth) || !$auth) {
    wp_safe_redirect(site_url());
    exit;
}

// Get user collections
$collections_result = get_user_collections([
    'nopaging'     => true,
    'nothumbnails' => true,
]);
$collections_map = array_column($collections_result['collections'], null, 'post_id');

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
            <div class="col md:col-span-6 py-6 md:py-12"
                x-data="{
                    collectionsMap: <?php echo clean_arr_for_doublequotes($collections_map); ?>,
                    applicationId:  '<?php echo get_the_ID(); ?>',
                    title:          '<?php echo clean_str_for_doublequotes(get_field('title')       ?? ''); ?>',
                    description:    '<?php echo clean_str_for_doublequotes(get_field('description') ?? ''); ?>',
                    showEditForm:   false,
                    _updateApplication(app) {
                        this.title       = app.title       || '';
                        this.description = app.description || '';
                        this.showEditForm = false;
                    },
                }"
                x-on:update-application="_updateApplication($event.detail.application)"
            >

                <a class="inline-flex items-center gap-1 text-14 text-black/60 hover:text-black mb-8 sm:mb-16" href="<?php echo site_url('/applications/'); ?>" >
                    <span>←</span>
                    <span>Back to Applications</span>
                </a>

                <div class="mb-6 md:mb-14 flex justify-start items-center flex-row">
                    <h1 class="font-bold text-25"><?php echo esc_html(get_post_meta(get_the_ID(), 'title', true) ?: 'Application'); ?></h1>
                </div>

                <!------------ Page Load Toasts ----------------->
                <div>
                    <?php if (!empty($_GET['toast']) and $_GET['toast'] == 'create') { ?><span x-init="$dispatch('success-toast', {'message': 'Application Created Successfully'});"></span><?php } ?>
                </div>

                <?php $default_tab = $_GET['tab'] ?? 'details'; ?>
                <div x-data="{
                    showApplicationDetails: <?php echo $default_tab === 'applicants' ? 'false' : 'true'; ?>,
                    showApplicants:         <?php echo $default_tab === 'applicants' ? 'true'  : 'false'; ?>,
                    hideTabs() {
                        this.showApplicationDetails = false;
                        this.showApplicants = false;
                    },
                }">
                    <!-- Tabs -->
                    <div class="flex items-start justify-between border-b border-black/20">
                        <div class="flex gap-6 items-start">
                            <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showApplicationDetails}" x-on:click="hideTabs(); showApplicationDetails = true;">Application Details</div>
                            <div class="preview-tab text-18 tab-heading pb-2 cursor-pointer" :class="{'active': showApplicants}" x-on:click="hideTabs(); showApplicants = true;">Applicants</div>
                        </div>
                    </div>

                    <div class="pt-4" x-show="showApplicationDetails" x-cloak>
                        <?php echo get_template_part('template-parts/applications/application-details', '', []); ?>
                    </div>

                    <div class="pt-4" x-show="showApplicants" x-cloak>
                        <?php echo get_template_part('template-parts/applications/applicants', '', []); ?>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<?php
get_footer();
