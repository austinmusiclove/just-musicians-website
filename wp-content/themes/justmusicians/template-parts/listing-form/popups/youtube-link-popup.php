<div class="popup-wrapper px-4 pt-12 w-screen h-screen fixed top-0 left-0 z-50 flex items-start sm:items-center justify-center" x-show="showYoutubeLinkPopup" x-cloak>
    <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>
    <div class="bg-white relative w-full max-h-[calc(100vh-8rem)] overflow-y-auto shadow-black-offset flex flex-col items-stretch" style="max-width: 780px;" x-on:click.away="showYoutubeLinkPopup = false">

        <div class="px-6 pt-4">

            <!-- Title -->
            <div class="flex items-center justify-between my-6">
                <h4 class="font-bold text-25 w-full" x-show="currentYtIndex < 0" x-cloak>Add a YouTube video</h4>
                <h4 class="font-bold text-25 w-full" x-show="currentYtIndex >= 0" x-cloak>Edit YouTube video settings</h4>
                <img class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" x-on:click="showYoutubeLinkPopup = false"/>
            </div>

            <!-- Toasts -->
            <div class="min-h-10" x-show="currentYtIndex < 0" x-cloak>
                <?php
                echo get_template_part('template-parts/global/toasts/success-toast', '', ['customEvent' => 'success-toast-youtube-link']);
                echo get_template_part('template-parts/global/toasts/error-toast',   '', ['customEvent' => 'error-toast-youtube-link']);
                ?>
            </div>

            <!-- Input for adding new youtube links -->
            <div class="sm:min-w-[500px] mb-10" x-show="currentYtIndex < 0" x-cloak>
                <label class="mb-1 inline-block">Video url</label>
                <div class="relative">
                    <input class="w-full mb-2 !pr-16" type="text" placeholder="https://"
                        x-ref="ytInput"
                        x-on:keydown.enter="$event.preventDefault(); _addYoutubeUrl($el)">
                    <button type="button" class="absolute top-2 right-2 w-fit rounded text-12 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black text-grey hover:text-black"
                        x-on:click="_addYoutubeUrl($refs.ytInput)"
                    >Add +</button>
                </div>
            </div>

            <!-- Edit youtube link settings -->
            <template x-for="(videoData, index) in youtubeVideoData" :key="index">
                <div class="grid sm:grid-cols-2" x-show="currentYtIndex >= 0 && index == currentYtIndex" x-cloak>
                    <div class="my-4" >
                        <img class="w-full" x-bind:src="`https://img.youtube.com/vi/${videoData.video_id}/mqdefault.jpg`">
                        <div class="mt-6">
                            <label class="mb-1 inline-block">Video Url</label>
                            <div class="text-14 text-grey break-words whitespace-normal" x-text="videoData.url"></div>
                        </div>
                        <div class="mt-6" x-data="{
                            calcStartTime() { return parseInt($refs.startMinute?.value || 0) * 60 + parseInt($refs.startSecond?.value || 0); },
                        }">
                            <label class="mb-1 inline-block">Start time</label><br>
                            <label class="mb-1 inline-block text-14">Minute</label>
                            <input class="w-full" type="number" min="0" x-ref="startMinute"
                                x-on:change="youtubeVideoData[index].start_time = calcStartTime()"
                                x-bind:value="Math.floor(videoData.start_time / 60)"
                            />
                            <label class="mb-1 inline-block text-14">Second</label>
                            <input class="w-full" type="number" min="0" max="59" x-ref="startSecond"
                                x-on:change="youtubeVideoData[index].start_time = calcStartTime()"
                                x-bind:value="videoData.start_time % 60"
                            />
                        </div>
                    </div>
                    <div class="sm:px-4 md:px-10 py-4">
                        <div class="-mx-6 pb-2 sm:pb-0 px-6">
                            <h5 class="font-bold text-18 mb-4">Link search terms</h5>
                            <p class="mb-4 text-16">Tag your videos with the appropriate search terms to help us show the most appropriate media to buyers.</p>
                            <div class="flex flex-wrap gap-x-1 gap-y-2 mb-4">
                                <div class="overflow-y-scroll max-h-[300px]">
                                    <?php $term_groups = [
                                        [ 'title' => 'Categories',      'selected' => 'categoriesCheckboxes', 'all' => $args['categories'],       ],
                                        [ 'title' => 'Instrumentation', 'selected' => 'instCheckboxes',       'all' => $args['instrumentations'], ],
                                        [ 'title' => 'Settings',        'selected' => 'settingsCheckboxes',   'all' => $args['settings'],         ],
                                        [ 'title' => 'Genres',          'selected' => 'genresCheckboxes',     'all' => $args['genres'],           ],
                                        [ 'title' => 'Subgenres',       'selected' => 'subgenresCheckboxes',  'all' => $args['subgenres'],        ],
                                    ];
                                    foreach ($term_groups as $group) { ?>
                                        <div x-show="<?php echo $group['selected']; ?>.length > 0">
                                            <h4 class="text-16 mb-3"><?php echo $group['title']; ?></h4>
                                            <div class="flex flex-wrap gap-x-1 gap-y-2 mb-4">
                                                <?php foreach ($group['all'] as $term) { ?>
                                                    <div class="w-fit cursor-pointer"
                                                        x-show="<?php echo $group['selected']; ?>.includes('<?php echo clean_str_for_doublequotes($term); ?>')" x-cloak
                                                        x-on:click="_toggleYoutubeLinkTerm(index, '<?php echo clean_str_for_doublequotes($term); ?>')"
                                                    >
                                                        <div class="flex items-center border border-black/20 px-3 h-7 rounded-full" :class="{
                                                            'bg-yellow-40':     youtubeVideoData[index]?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                            'font-bold':        youtubeVideoData[index]?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                            'border':          !youtubeVideoData[index]?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                            'border-black/20': !youtubeVideoData[index]?.mediatags.includes('<?php echo clean_str_for_doublequotes($term); ?>'),
                                                        }">
                                                            <span class="text-14 w-fit"><?php echo $term; ?></span>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <p class="text-14 text-grey mb-2">
                                    <span x-text="youtubeVideoData[index]?.mediatags.length"></span>/
                                    <span x-text="categoriesCheckboxes.length + genresCheckboxes.length + subgenresCheckboxes.length + instCheckboxes.length + settingsCheckboxes.length"></span>
                                    <span> terms selected</span>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            </template>

        </div>

        <div class="bg-yellow-20 pl-4 py-2 pr-2 flex items-center justify-between gap-4" x-show="currentYtIndex >= 0" x-cloak>
            <span class="text-16">Add more search terms to your listing to see more options.</span>
            <button class="w-fit rounded text-14 bg-white hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="showYoutubeLinkPopup = false">Apply</button>
        </div>

    </div>
</div>
