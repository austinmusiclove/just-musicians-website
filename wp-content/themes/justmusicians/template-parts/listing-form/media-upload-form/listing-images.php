<div class="flex flex-col h-full p-2">

    <div class="hidden">
        <input name="listing_images_input" type="file" accept="image/png, image/jpeg, image/jpg, image/webp"
            x-ref="listingImageInput"
            x-on:change="if ($event.target.files.length > 0) { _initCropperFromFile($event, $refs.cropperDisplay, 'listing_images', ''); showImageEditPopup = true; } $el.value = null;"
        >
        <input id="listing_images_files" name="listing_images[]" type="file" accept="image/*">
        <input name="listing_images_meta" type="hidden" x-bind:value="JSON.stringify(orderedImageData['listing_images'])">
    </div>

    <div class="relative -mx-2 sm:mx-0 pt-1">
        <h3 class="font-bold text-18">Listing Images</h3>
        <!-- Buttons - screen 2 -->
        <div class="flex gap-2 items-center absolute right-0 top-0" x-show="orderedImageData['listing_images'].length > 0" x-cloak>
            <!--<button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Bulk delete</button>-->
            <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="$refs.listingImageInput.click()">Upload +</button>
        </div>
    </div>

    <!-- Screen 1 -->
    <div class="flex flex-col items-center justify-center grow text-center px-8" x-show="orderedImageData['listing_images'].length == 0" x-cloak>
        <div>These images will be added to the media gallery on your listing page.</div>
        <button type="button" class="w-fit rounded text-14 mt-4 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="$refs.listingImageInput.click()">Upload +</button>
    </div>

    <!-- Screen 2 -->
    <div class="flex flex-col grow -mx-2 mt-8"
        x-show="orderedImageData['listing_images'].length > 0" x-cloak
        x-sort="orderedImageData['listing_images'].splice($position, 0, orderedImageData['listing_images'].splice($item, 1)[0]); _updateFileInputs('listing_images');"
    >
        <template x-for="(data, index) in orderedImageData['listing_images']" :key="index + data.image_id">
            <div class="flex min-w-0 items-center justify-between gap-6 sm:pl-3 sm:pr-2 py-2 border-b border-black/20 last:border-none w-full" x-sort:item="index">
                <div class="flex items-center gap-4 min-w-0">
                    <!--<label class="custom-checkbox -mt-1"><input type="checkbox"/><span class="checkmark"></span></label>-->
                    <div class="cursor-grabbing bg-gray-200 px-2 py-1" x-sort:handle>☰</div>
                    <div class="aspect-4/3 w-16 shrink-0">
                        <img class="w-full h-full object-cover" x-bind:src="_getImageData('listing_images', data.image_id)?.url" x-show="!_getImageData('listing_images', data.image_id)?.loading" x-cloak >
                        <div class="flex h-4" x-show="_getImageData('listing_images', data.image_id)?.loading" x-cloak>
                            <span class="flex mr-4 mt-1"> <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'grey']); ?> </span>
                            <span>Processing image...</span>
                        </div>
                    </div>
                    <div class="overflow-hidden" x-show="!_getImageData('listing_images', data.image_id)?.loading" x-cloak>
                        <div class="text-14 text-grey truncate overflow-hidden whitespace-nowrap grow-0 shrink min-w-0" x-text="_getImageData('listing_images', data.image_id)?.filename"></div>
                        <div class="tags flex flex-wrap gap-1 min-w-0">
                            <template x-for="tag in _getImageData('listing_images', data.image_id)?.mediatags" :key="tag">
                                <div class="w-fit flex items-center text-14 whitespace-nowrap bg-yellow-20 px-3 h-6 rounded-full" x-text="tag"></div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:opacity-50"
                        x-on:touchend="$event.preventDefault(); $refs.cropperDisplay.src = _getImageData('listing_images', data.image_id)?.url; _initCropper($refs.cropperDisplay, 'listing_images', data.image_id); showImageEditPopup = true;"
                        x-on:click="$refs.cropperDisplay.src = _getImageData('listing_images', data.image_id)?.url; _initCropper($refs.cropperDisplay, 'listing_images', data.image_id); showImageEditPopup = true;"
                        x-bind:disabled="_getImageData('listing_images', data.image_id)?.loading"
                    >Edit</button>
                    <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white"
                        x-on:touchend="$event.preventDefault(); _removeImage('listing_images', data.image_id)"
                        x-on:click="_removeImage('listing_images', data.image_id)"
                    >Delete</button>
                </div>
            </div>
        </template>

    </div>

</div>
