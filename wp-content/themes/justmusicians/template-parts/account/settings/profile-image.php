
<div class="flex flex-col gap-2">


    <!-- Profile Image Label -->
    <label class="flex gap-1">
        <span class="text-18 mb-1 inline-block">Profile Image <span class="text-red">*</span></span>
        <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'This is the image that other users will see when they receive messages from you.' ]); ?>
    </label>


    <!-- Hidden Inputs -->
    <div class="hidden">
        <input name="profile_image_input" type="file" accept="image/png, image/jpeg, image/jpg, image/webp"
            x-ref="profileImageInput"
            x-on:change="if ($event.target.files.length > 0) { _initCropperFromFile($event, $refs.cropperDisplay); } $el.value = null;"
        >
        <input name="profile_image" type="file" accept="image/*" x-ref="profile_image_file">
        <input name="profile_image_meta" type="hidden" x-bind:value="JSON.stringify(accountSettings.profile_image)">
    </div>


    <!-- User Controls -->
    <div class="flex flex-col gap-4">

        <!-- Buttons -->
        <div class="flex gap-4">
            <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:opacity-50"
                x-bind:disabled="imageProcessing"
                x-show="cropper" x-cloak
                x-on:click="_closeCropper();"
            >Done</button>
            <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:opacity-50"
                x-bind:disabled="imageProcessing || accountSettings.profile_image.is_default"
                x-show="!cropper" x-cloak
                x-on:click="_initCropper($refs.cropperDisplay);"
            >Edit</button>
            <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:opacity-50"
                x-bind:disabled="imageProcessing"
                x-on:click="$refs.profileImageInput.click()"
            >Replace</button>
        </div>

        <!-- Image Display -->
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
    </div>



</div>
