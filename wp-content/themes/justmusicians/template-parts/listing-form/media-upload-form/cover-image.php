<div class="flex flex-col h-full p-2 overflow-hidden">

    <div class="hidden">
        <input name="cover_image_input" type="file" accept="image/png, image/jpeg, image/jpg, image/webp"
            x-ref="coverImageInput"
            x-on:change="if ($event.target.files.length > 0) { _initCropperFromFile($event, $refs.cropperDisplay, 'cover_image', 'cover_image'); showImageEditPopup = true; } $el.value = null;"
        >
        <input name="cover_image" type="file" accept="image/*" x-ref="cover_image_file">
        <input name="cover_image_meta" type="hidden" x-bind:value="JSON.stringify(_getImageData('cover_image', 'cover_image'))">
    </div>

    <div id="cover-image" class="relative -mx-2 sm:mx-0 pt-1">
        <h3 class="font-bold text-18">Cover Image</h3>
    </div>


    <!-- State 1 -->
    <div class="flex flex-col items-center justify-center grow text-center px-8" x-show="!_getImageData('cover_image', 'cover_image')?.url" x-cloak>
        <div>This field is for the large image that appears at the top of your profile page.</div>
        <button type="button" class="w-fit rounded text-14 mt-4 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="$refs.coverImageInput.click()">Upload +</button>
    </div>

    <!-- State 2 -->
    <div class="overflow-hidden flex items-center justify-center grow mt-4 pb-2" x-show="_getImageData('cover_image', 'cover_image')?.url" x-cloak>
        <div class="grid grid-cols-2 gap-2">
            <div>
                <div class="aspect-4/3 w-full max-w-full max-h-full">
                    <img class="h-full object-cover" x-bind:src="_getImageData('cover_image', 'cover_image')?.url">
                </div>
            </div>
            <div class="flex items-center flex-col justify-center gap-4 px-4">
                <!--<div class="text-center text-14">The large image that appears at the top of your profile page.</div>-->
                <div class="text-center text-grey" x-text="_getImageData('cover_image', 'cover_image')?.filename"></div>
                <div class="flex gap-2">
                    <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white"
                        x-on:click="$refs.cropperDisplay.src = _getImageData('cover_image', 'cover_image')?.url; _initCropper($refs.cropperDisplay, 'cover_image', 'cover_image'); showImageEditPopup = true;"
                    >Edit</button>
                    <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white"
                        x-on:click="_removeImage('cover_image', 'cover_image')"
                    >Delete</button>
                </div>
                <div class="tags flex flex-wrap gap-1 min-w-0 max-h-24 overflow-scroll">
                    <template x-for="tag in _getImageData('cover_image', 'cover_image')?.mediatags" :key="tag">
                        <div class="w-fit flex items-center text-14 whitespace-nowrap bg-yellow-20 px-3 h-6 rounded-full" x-text="tag"></div>
                    </template>
                </div>
                <!--
                -->
            </div>
        </div>
    </div>


</div>
