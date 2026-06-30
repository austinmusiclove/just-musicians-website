<!-- Details -->
<div class="flex flex-col gap-4" x-show="!showEditForm" x-cloak>

    <!-- Title -->
    <div>
        <h3 class="font-bold text-16 mb-2">Application Title</h3>
        <p class="text-16 whitespace-pre-wrap" :class="title ? '' : 'text-black/50'" x-text="title ? title : 'No title provided'"></p>
    </div>

    <!-- Description -->
    <div>
        <h3 class="font-bold text-16 mb-2">Description</h3>
        <p class="text-16 whitespace-pre-wrap" :class="description ? '' : 'text-black/50'" x-text="description ? description : 'No description provided'"></p>
    </div>

    <!-- Application Link -->
    <div>
        <h3 class="font-bold text-16 mb-2">Application Link</h3>
        <div class="flex flex-row gap-1">
            <?php echo get_template_part('template-parts/global/copy-to-clipboard', '', [
                'text' => get_musician_application_url(get_the_ID()),
                'external_link' => esc_url(get_musician_application_url(get_the_ID())),
            ]); ?>
        </div>
    </div>

    <!-- Actions -->
    <div class="pt-4 flex gap-2">
        <button type="button" x-on:click="showEditForm = true" class="bg-yellow hover:bg-navy text-black hover:text-white px-6 py-3 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap inline-block">
            Edit Application
        </button>
        <button type="button" class="bg-white hover:bg-red hover:text-white border border-black/20 hover:border-red px-6 py-3 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap inline-block"
            hx-delete="<?php echo site_url('/wp-html/v1/applications/' . get_the_ID()); ?>"
            hx-confirm="Are you sure you want to delete this application?"
            hx-target="#delete-result"
        >
            Delete Application
            <span id="delete-result"></span>
        </button>
    </div>

</div>

<!-- Edit form -->
<div x-show="showEditForm">
    <?php echo get_template_part('template-parts/applications/edit-application-form', '', []); ?>
</div>
