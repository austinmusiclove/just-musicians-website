<div class="hidden">
    <input name="profile_image_input" type="file" accept="image/png, image/jpeg, image/jpg, image/webp"
        x-ref="profileImageInput"
        x-on:change="if ($event.target.files.length > 0) { _initCropperFromFile($event, $refs.cropperDisplay); } $el.value = null;"
    >
    <input name="profile_image" type="file" accept="image/*" x-ref="profile_image_file">
    <input name="profile_image_meta" type="hidden" x-bind:value="JSON.stringify(accountSettings.profile_image)">
</div>
