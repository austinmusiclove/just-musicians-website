<div class="aspect-square w-full max-w-full max-h-full">
    <img class="h-full object-cover"
        x-ref="cropperDisplay"
        x-bind:src="accountSettings.profile_image.url"
    >
    <div class="flex h-4" x-show="imageProcessing" x-cloak>
        <span class="flex mr-4 mt-1"> <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'grey']); ?> </span>
        <span>Processing image...</span>
    </div>
</div>
