<div class="flex flex-col h-full p-2">

    <div class="hidden">
        <input name="listing_images_input" type="file" accept="image/png, image/jpeg, image/jpg, image/webp"
            x-ref="listingImageInput"
            x-on:change="if ($event.target.files.length > 0) { _initCropperFromFile($event, $refs.cropperDisplay, 'listing_images', ''); showImageEditPopup = true; } $el.value = null;"
        >
        <input name="listing_images[]" type="file" accept="image/*" x-ref="listing_images_file">
        <input name="listing_images_meta" type="hidden" x-bind:value="JSON.stringify(imageData['listing_images'])">
        <input name="ordered_listing_images[]" type="hidden">
        <template x-for="imageId in orderedImages['listing_images']" :key="imageId">
            <input name="ordered_listing_images[]" type="hidden" x-bind:value="imageId">
        </template>
    </div>

    <div class="relative -mx-2 sm:mx-0 pt-1">
        <h3 class="font-bold text-18">Listing Images</h3>
        <!-- Buttons - screen 2 -->
        <div class="flex gap-2 items-center absolute right-0 top-0" x-show="orderedImages['listing_images'].length > 0" x-cloak>
            <!--<button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Bulk delete</button>-->
            <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="$refs.listingImageInput.click()">Upload +</button>
        </div>
    </div>

    <!-- Screen 1 -->
    <div class="flex flex-col items-center justify-center grow text-center px-8" x-show="orderedImages['listing_images'].length == 0" x-cloak>
        <div>These images will be added to the media gallery on your listing page.</div>
        <button type="button" class="w-fit rounded text-14 mt-4 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="$refs.listingImageInput.click()">Upload +</button>
    </div>

    <!-- Screen 2 -->
    <div class="flex flex-col grow -mx-2 mt-8" x-show="orderedImages['listing_images'].length > 0" x-cloak x-sort="_reorderImage('listing_images', $item, $position); _updateFileInputs();">
        <template x-for="imageId in listingImages" :key="imageId">
            <div class="flex min-w-0 items-center justify-between gap-6 sm:pl-3 sm:pr-2 py-2 border-b border-black/20 last:border-none w-full" x-sort:item="imageId">
                <div class="flex items-center gap-4 min-w-0">
                    <!--<label class="custom-checkbox -mt-1"><input type="checkbox"/><span class="checkmark"></span></label>-->
                    <div class="aspect-4/3 w-16 shrink-0">
                        <img class="w-full h-full object-cover" x-bind:src="imageData['listing_images'][imageId]?.url">
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-14 text-grey mb-2" x-text="imageData['listing_images'][imageId]?.filename"></div>
                        <div class="tags flex flex-wrap gap-1 min-w-0">
                            <template x-for="tag in imageData['listing_images'][imageId]?.mediatags" :key="tag">
                                <div class="w-fit flex items-center text-14 whitespace-nowrap bg-yellow-20 px-3 h-6 rounded-full" x-text="tag"></div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white"
                        x-on:click="$refs.cropperDisplay.src = imageData['listing_images'][imageId]?.url; _initCropper($refs.cropperDisplay, 'listing_images', imageId); showImageEditPopup = true;"
                    >Edit</button>
                    <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white"
                        x-on:click="_removeImage('listing_images', imageId)"
                    >Delete</button>
                </div>
            </div>
        </template>

    </div>

</div>
