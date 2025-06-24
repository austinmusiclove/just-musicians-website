<div class="flex flex-col h-full p-2">

    <div class="hidden">
        <input name="stage_plots_input" type="file" accept="image/png, image/jpeg, image/jpg, image/webp"
            x-ref="stagePlotInput"
            x-on:change="if ($event.target.files.length > 0) { _initCropperFromFile($event, $refs.stagePlotCropperDisplay, 'stage_plots', ''); showStagePlotPopup = true; } $el.value = null;"
        >
        <input name="stage_plots[]" type="file" accept="image/*" x-ref="stage_plots_file">
        <input name="stage_plots_meta" type="hidden" x-bind:value="JSON.stringify(imageData['stage_plots'])">
        <input name="ordered_stage_plots[]" type="hidden">
        <template x-for="imageId in orderedImages['stage_plots']" :key="imageId">
            <input name="ordered_stage_plots[]" type="hidden" x-bind:value="imageId">
        </template>
    </div>

    <div class="relative -mx-2 sm:mx-0 pt-1">
        <h3 class="font-bold text-18 flex items-center gap-1">Stage Plot Images</h3>
        <!-- Buttons - screen 3 -->
        <div class="flex gap-2 items-center absolute right-0 top-0" x-show="orderedImages['stage_plots'].length > 0" x-cloak>
            <!--<button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Bulk delete</button>-->
            <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="$refs.stagePlotInput.click()">Upload +</button>
        </div>
    </div>

    <!-- Screen 1 -->
    <div class="flex flex-col items-center justify-center grow text-center px-8" x-show="orderedImages['stage_plots'].length == 0" x-cloak>
        <div>Consider uploading one or more stage plots to illustrate your stage equipment needs to sound engineers.</div>
        <button type="button" class="w-fit rounded text-14 mt-4 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="$refs.stagePlotInput.click()">Upload +</button>
    </div>

    <!-- Screen 3 -->
    <div class="flex flex-col grow -mx-2 mt-8" x-show="orderedImages['stage_plots'].length > 0" x-cloak x-sort="_reorderImage('stage_plots', $item, $position); _updateFileInputs();">
        <template x-for="imageId in stagePlots" :key="imageId">
            <div class="flex items-center justify-between gap-6 sm:pl-3 sm:pr-2 py-2 border-b border-black/20 last:border-none w-full" x-sort:item="imageId">
                <div class="flex items-center gap-4 grow min-w-0">
                    <!--<label class="custom-checkbox -mt-1"><input type="checkbox"/><span class="checkmark"></span></label>-->
                    <div class="aspect-4/3 w-16 border border-black/20">
                        <img class="w-full h-full object-cover" x-bind:src="imageData['stage_plots'][imageId]?.url">
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-14 text-grey truncate whitespace-nowrap grow-0 shrink min-w-0" x-text="imageData['stage_plots'][imageId]?.filename"></div>
                        <div class="text-14 text-grey truncate whitespace-nowrap grow-0 shrink min-w-0" x-text="imageData['stage_plots'][imageId]?.caption"></div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white"
                        x-on:click="$refs.stagePlotCropperDisplay.src = imageData['stage_plots'][imageId]?.url; _initCropper($refs.stagePlotCropperDisplay, 'stage_plots', imageId); showStagePlotPopup = true;"
                    >Edit</button>
                    <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white"
                        x-on:click="_removeImage('stage_plots', imageId)"
                    >Delete</button>
                </div>
            </div>
        </template>

    </div>



</div>
