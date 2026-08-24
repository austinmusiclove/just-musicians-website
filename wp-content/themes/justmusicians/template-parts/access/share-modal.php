<div class="popup-wrapper w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center p-4 sm:p-8" x-show="showShareModal" x-cloak>

    <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"
        x-on:click="showShareModal = false"
    ></div>

    <div class="bg-white relative w-full h-full md:w-auto md:h-auto flex items-center justify-center p-4 sm:p-8" style="max-width: 780px;">

        <!-- X button -->
        <img class="close-button opacity-60 hover:opacity-100 absolute top-2 right-2 cursor-pointer"
            src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>"
            x-on:click="showShareModal = false;"
        />

        <!-- Share form -->
        <form id="share-access-form" class="p-8 w-full" style="width: 500px;">

            <h2 class="text-22 font-sun-motter mb-2"><?php echo esc_html($args['heading']); ?></h2>
            <p class="text-14 text-black/60 mb-4">Give another user access to this <?php echo esc_html(strtolower($args['heading'])); ?>.</p>

            <!-- Users with access -->
            <div id="share-access-list-<?php echo esc_attr($args['subject_id']); ?>"
                hx-get="<?php echo site_url('/wp-html/v1/access/'); ?>"
                hx-trigger="load-share-access from:body"
                hx-vals='{"subject_id": "<?php echo esc_attr($args['subject_id']); ?>", "subject_type": "<?php echo esc_attr($args['subject_type']); ?>"}'
                hx-target="#share-access-list-<?php echo esc_attr($args['subject_id']); ?>"
                hx-swap="outerHTML">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
            </div>

            <input type="email" name="email" placeholder="Email address" class="w-full border-2 border-black/20 rounded-sm p-3 text-14 mt-4 focus:border-yellow outline-none" x-model="shareEmail" />

            <select name="access_type" class="w-full border-2 border-black/20 rounded-sm p-3 text-14 mt-4 bg-white focus:border-yellow outline-none" x-model="shareAccessType">
                <option value="<?php echo esc_attr(HM_VIEW_TYPE_MGMT); ?>">View</option>
                <option value="<?php echo esc_attr(HM_EDIT_TYPE_MGMT); ?>">Edit</option>
            </select>

            <input type="hidden" name="subject_id" value="<?php echo esc_attr($args['subject_id']); ?>" />
            <input type="hidden" name="subject_type" value="<?php echo esc_attr($args['subject_type']); ?>" />

            <div class="flex justify-end gap-2 mt-6">
                <button type="button"
                    class="border-2 border-black px-4 py-2 text-14 font-sun-motter hover:bg-black/5"
                    x-on:click="showShareModal = false;"
                >Cancel</button>
                <button type="button"
                    class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-14 px-5 py-2 hover:bg-navy hover:text-white"
                    hx-post="<?php echo site_url('/wp-html/v1/access/'); ?>"
                    hx-target="#share-mdl-results-<?php echo esc_attr($args['subject_id']); ?>"
                    hx-swap="outerHTML"
                    hx-indicator="#share-access-button-content"
                    hx-include="#share-access-form"
                >
                    <span id="share-access-button-content" class="flex justify-center">
                        <span class="htmx-indicator-component-block-replace">Share</span>
                        <span class="htmx-indicator-component-block">
                            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
                        </span>
                    </span>
                </button>
            </div>
            <div id="share-mdl-results-<?php echo esc_attr($args['subject_id']); ?>"></div>

        </form>

    </div>

</div>
