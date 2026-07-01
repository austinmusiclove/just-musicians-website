<div class="popup-wrapper px-4 pt-12 w-screen h-screen fixed top-0 left-0 z-50 flex items-start sm:items-center justify-center" x-show="showImageEditPopup" x-cloak>
    <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>
    <div class="bg-white relative w-full max-h-[calc(100vh-4rem)] sm:max-h-[calc(100vh-8rem)] flex flex-col overflow-hidden shadow-black-offset" style="max-width: 780px;" x-on:click.away="showImageEditPopup = false;">

        <div class="px-6 pt-4 flex flex-col flex-1 min-h-0 overflow-hidden">

            <div class="flex items-center justify-between my-6 flex-shrink-0">
                <h4 class="font-bold text-25 w-full">Crop your image</h4>
                <img class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer flex-shrink-0" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" x-on:click="showImageEditPopup = false;"/>
            </div>

            <!-- Cropper display -->
            <div class="flex-1 min-h-0 overflow-hidden flex flex-col">
                <div class="w-full h-full flex-1 min-h-0" x-show="showCropperDisplay" x-cloak>
                    <img class="block max-w-full h-full" x-ref="cropperDisplay" />
                </div>
                <div class="flex h-4 flex-shrink-0" x-show="popupImageSpinner" x-cloak>
                    <span class="flex mr-4 mt-1"> <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'grey']); ?> </span>
                    <span>Processing image...</span>
                </div>
            </div>

        </div>

        <div class="bg-yellow-20 pl-4 py-2 pr-2 flex items-center justify-end gap-4 flex-shrink-0">
            <button type="button" class="w-fit rounded text-14 bg-white hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="showImageEditPopup = false;">Apply</button>
        </div>

    </div>
</div>
