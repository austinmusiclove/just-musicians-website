<!-- Details -->
<div class="flex flex-col gap-y-2" x-show="!showEditForm" x-cloak>


    <!-- Title -->
    <h3 class="font-bold text-14 mt-4">Application Title</h3>
    <p class="text-14 whitespace-pre-wrap" :class="title ? '' : 'text-black/50'" x-text="title ? title : 'No title provided'"></p>

    <!-- Description -->
    <h3 class="font-bold text-14 mt-4">Description</h3>
    <p class="text-14 whitespace-pre-wrap" :class="description ? '' : 'text-black/50'" x-text="description ? description : 'No description provided'"></p>

    <!-- Application Link -->
    <div class="flex items-center gap-1 mt-4">
        <h3 class="font-bold text-14">Application Link: </h3>
        <?php echo get_template_part('template-parts/global/copy-to-clipboard', '', [
            'text' => get_musician_application_url(get_the_ID()),
        ]); ?>
    </div>

    <button type="button" x-on:click="showEditForm = true" class="hover:bg-yellow-light bg-yellow font-sun-motter w-fit px-3 py-2 rounded-sm text-14 mt-4">Edit Application</button>


</div>

<!-- Edit form -->
<div x-show="showEditForm">
    <?php echo get_template_part('template-parts/applications/edit-application-form', '', []); ?>
</div>
