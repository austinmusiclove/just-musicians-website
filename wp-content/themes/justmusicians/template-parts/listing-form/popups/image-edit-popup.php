<div class="popup-wrapper px-4 pt-12 w-screen h-screen fixed top-0 left-0 z-50 flex items-center justify-center" x-show="showImageEditPopup" x-cloak>
    <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>
        <div class="bg-white relative w-auto h-auto gap-4 shadow-black-offset flex flex-col items-stretch justify-center" style="max-width: 780px;" x-on:click.away="showImageEditPopup = false">
        <div class="px-6 pt-4">

            <div class="flex items-center justify-between mb-6">
                <h4 class="font-bold text-25 w-full">Crop your image</h4>
                <img class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" x-on:click="showImageEditPopup = false"/>
            </div>

            <div class="grid sm:grid-cols-2">

                <!-- Cropper display -->
                <div class="my-4 max-h-[600px]" >
                    <img class="block max-w-full" x-ref="cropperDisplay" />
                    <div class="flex h-4" x-show="showImageProcessingSpinner" x-cloak>
                        <span class="flex mr-4 mt-1"> <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'grey']); ?> </span>
                        <span>Processing image...</span>
                    </div>
                </div>

                <!-- Mediatags -->
                <div class="sm:px-4 md:px-10 py-4">
                    <h5 class="font-bold text-18 mb-3">Link search terms</h5>
                    <p class="mb-4 text-16">Tag your images with the appropriate search terms to help us show the most appropriate media to buyers.</p>

                    <template x-for="imageType in ['cover_image', 'listing_images']" :key="imageType">
                        <template x-for="data in orderedImageData[imageType]" :key="data.image_id">
                            <span x-show="data.image_id == currentImageId">
                                <p class="text-14 text-grey mb-2">
                                    <span x-text="_getImageData(imageType, data.image_id)?.mediatags.length"></span>/
                                    <span x-text="categoriesCheckboxes.length + instCheckboxes.length + settingsCheckboxes.length"></span>
                                    <span> terms selected</span>
                                </p>

                                <div class="overflow-y-scroll max-h-[300px]">
                                    <?php $term_groups = [
                                        [ 'title' => 'Categories',      'selected' => 'categoriesCheckboxes', 'all' => $args['categories'],       ],
                                        [ 'title' => 'Instrumentation', 'selected' => 'instCheckboxes',       'all' => $args['instrumentations'], ],
                                        [ 'title' => 'Settings',        'selected' => 'settingsCheckboxes',   'all' => $args['settings'],         ],
                                    ];
                                    foreach ($term_groups as $group) { ?>
                                        <div x-show="<?php echo $group['selected']; ?>.length > 0">
                                            <h4 class="text-16 mb-3"><?php echo $group['title']; ?></h4>
                                            <div class="flex flex-wrap gap-x-1 gap-y-2 mb-4">
                                                <?php foreach ($group['all'] as $term) { ?>
                                                    <div class="w-fit cursor-pointer"
                                                        x-show="<?php echo $group['selected']; ?>.includes('<?php echo clean_str_for_doublequotes($term); ?>') ||
                                                                _getImageData(imageType, data.image_id)?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>')"
                                                        x-cloak
                                                        x-on:click="_toggleImageTerm(imageType, data.image_id, '<?php echo clean_str_for_doublequotes($term); ?>')"
                                                    >
                                                        <div class="flex items-center border border-black/20 px-3 h-7 rounded-full" :class="{
                                                            'bg-yellow-40':     _getImageData(imageType, data.image_id)?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                            'font-bold':        _getImageData(imageType, data.image_id)?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                            'border':          !_getImageData(imageType, data.image_id)?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                            'border-black/20': !_getImageData(imageType, data.image_id)?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                        }">
                                                            <span class="text-14 w-fit"><?php echo $term; ?></span>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </span>
                        </template>
                    </template>

                </div>
            </div>
        </div>


        <div class="bg-yellow-20 pl-4 py-2 pr-2 flex items-center justify-between gap-4">
            <span class="text-16">Add more search terms to your listing to see more options.</span>
            <button class="w-fit rounded text-14 bg-white hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="showImageEditPopup = false">Apply</button>
        </div>

    </div>
</div>
