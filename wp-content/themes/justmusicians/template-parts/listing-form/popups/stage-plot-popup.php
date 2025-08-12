<div class="popup-wrapper px-4 pt-12 w-screen h-screen fixed top-0 left-0 z-50 flex items-start sm:items-center justify-center" x-show="showStagePlotPopup" x-cloak>
    <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>
    <div class="bg-white relative w-full max-h-[calc(100vh-8rem)] overflow-y-auto shadow-black-offset flex flex-col items-stretch" style="max-width: 780px;" x-on:click.away="showStagePlotPopup = false">

        <div class="px-6 pt-4">
            <div class="flex items-center justify-between my-6">
                <h4 class="font-bold text-25 w-full">Add a stage plot image</h4>
                <img class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" x-on:click="showStagePlotPopup = false"/>
            </div>

            <div>
                <div class="mb-6 grid sm:grid-cols-2 gap-2">

                    <!-- Cropper display -->
                    <div class="my-4" >
                        <div class="w-full" x-show="showCropperDisplay" x-claok>
                            <img x-ref="stagePlotCropperDisplay" />
                        </div>
                        <div class="flex h-4" x-show="popupImageSpinner" x-cloak>
                            <span class="flex mr-4 mt-1"> <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'grey']); ?> </span>
                            <span>Processing image...</span>
                        </div>
                    </div>

                    <!-- Filename -->
                    <template x-for="data in orderedImageData['stage_plots']" :key="data.image_id">
                        <div class="max-w-1/2 break-all text-center flex items-center flex-col justify-center gap-4 px-4 text-center text-grey"
                            x-show="data.image_id == currentImageId"
                            x-text="_getImageData('stage_plots', data.image_id)?.filename">
                        </div>
                    </template>

                </div>

                <!-- Caption -->
                <div class="border-t border-black/20 -mx-6 pt-4 px-6 mb-6">
                    <label class="mb-1 inline-block">Caption</label>
                    <template x-for="data in orderedImageData['stage_plots']" :key="data.image_id">
                        <input type="text" name="stage_plot_caption"
                            x-show="data.image_id == currentImageId"
                            x-bind:value="_getImageData('stage_plots', data.image_id)?.caption"
                            x-on:change="_getImageData('stage_plots', data.image_id).caption = $event.target.value"
                        />
                    </template>
                </div>

            </div>
        </div>


        <div class="bg-yellow-20 pl-4 py-2 pr-2 flex items-center justify-between gap-4">
            <span class="text-16">Let potential clients know what this is.</span>
            <button type="button" class="w-fit rounded text-14 bg-white hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="showStagePlotPopup = false">Apply</button>
        </div>

    </div>
</div>
