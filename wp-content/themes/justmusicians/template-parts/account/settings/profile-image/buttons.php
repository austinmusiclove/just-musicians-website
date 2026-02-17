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
        x-bind:disabled="cropper || imageProcessing"
        x-on:click="$refs.profileImageInput.click()"
    >Replace</button>
</div>
