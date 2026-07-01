<div class="popup-wrapper px-4 pt-12 w-screen h-screen fixed top-0 left-0 z-50 flex items-start sm:items-center justify-center" x-show="showYoutubeLinkPopup" x-cloak>
    <div class="popup-close-bg bg-black/40 absolute top-0 left-0 w-full h-full cursor-pointer"></div>
    <div class="bg-white relative w-full max-h-[calc(100vh-8rem)] flex flex-col overflow-hidden shadow-black-offset" style="max-width: 780px;" x-on:click.away="showYoutubeLinkPopup = false">

        <div class="px-6 pt-4 flex flex-col flex-1 min-h-0 overflow-y-auto">

            <!-- Title -->
            <div class="flex items-center justify-between my-6 flex-shrink-0">
                <h4 class="font-bold text-25 w-full" x-show="currentYtIndex < 0" x-cloak>Add a YouTube video</h4>
                <h4 class="font-bold text-25 w-full" x-show="currentYtIndex >= 0" x-cloak>Edit YouTube video settings</h4>
                <img class="close-button -mr-3 opacity-60 hover:opacity-100 cursor-pointer flex-shrink-0" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" x-on:click="showYoutubeLinkPopup = false"/>
            </div>

            <!-- Toasts -->
            <div class="min-h-10 flex-shrink-0" x-show="currentYtIndex < 0" x-cloak>
                <?php
                echo get_template_part('template-parts/global/toasts/success-toast', '', ['customEvent' => 'success-toast-youtube-link']);
                echo get_template_part('template-parts/global/toasts/error-toast',   '', ['customEvent' => 'error-toast-youtube-link']);
                ?>
            </div>

            <!-- Input for adding new youtube links -->
            <div class="sm:min-w-[500px] mb-10 flex-shrink-0" x-show="currentYtIndex < 0" x-cloak>
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
                <div x-show="currentYtIndex >= 0 && index == currentYtIndex" x-cloak>
                    <div class="my-4 flex-shrink-0">
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
                </div>
            </template>

        </div>

        <div class="bg-yellow-20 pl-4 py-2 pr-2 flex items-center justify-end gap-4 flex-shrink-0" x-show="currentYtIndex >= 0" x-cloak>
            <button type="button" class="w-fit rounded text-14 bg-white hover:bg-navy hover:text-white group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white" x-on:click="showYoutubeLinkPopup = false">Apply</button>
        </div>

    </div>
</div>
