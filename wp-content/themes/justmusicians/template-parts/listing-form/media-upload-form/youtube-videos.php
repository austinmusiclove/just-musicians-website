<div class="flex flex-col h-full w-full p-2">

    <input type="hidden" name="youtube_video_data" x-bind:value="JSON.stringify(youtubeVideoData)">

    <div class="relative -mx-2 sm:mx-0 pt-1">
        <h3 class="font-bold text-18">YouTube Videos</h3>
        <!-- Buttons - screen 3 -->
        <div class="flex gap-2 items-center absolute right-0 top-0" x-show="youtubeVideoData.length > 0" x-cloak>
            <!--<button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Bulk delete</button>-->
            <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white"
                x-on:click="currentYtIndex = -1; showYoutubeLinkPopup = true; $nextTick(() => { $refs.ytInput.focus(); });"
            >Upload +</button>
        </div>
    </div>

    <!-- Screen 1 -->
    <div class="flex flex-col items-center justify-center grow" x-show="youtubeVideoData.length == 0" x-cloak>
        <div class="text-center">No YouTube videos yet.</div>
        <button type="button" class="w-fit rounded text-14 mt-4 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white"
            x-on:click="currentYtIndex = -1; showYoutubeLinkPopup = true; $nextTick(() => { $refs.ytInput.focus(); });"
        >Upload +</button>
    </div>

    <!-- Screen 3 -->
    <div class="flex flex-col grow -mx-2 mt-6"
        x-show="youtubeVideoData.length > 0" x-cloak
        x-sort="reorder($item, $position);"
        x-data="{
            reorder(fromIndex, toIndex) {
                youtubeVideoData.splice(toIndex, 0, youtubeVideoData.splice(fromIndex, 1)[0]);
            }
        }"
    >
        <template x-for="(videoData, index) in youtubeVideoData" :key="index + videoData.url">
            <div class="flex items-center justify-between gap-6 sm:pl-3 sm:pr-2 py-2 border-b border-black/20 last:border-none w-full cursor-grabbing" x-sort:item="index">
                <div class="flex items-center gap-4 grow min-w-0"
                    x-on:click="currentYtIndex = index; showYoutubeLinkPopup = true;"
                >
                    <!--<label class="custom-checkbox -mt-1"><input type="checkbox"/><span class="checkmark"></span></label>-->
                    <div class="aspect-video w-16 shrink-0">
                        <img class="w-full h-full object-cover" x-bind:src="`https://img.youtube.com/vi/${videoData.video_id}/default.jpg`">
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-14 text-grey truncate overflow-hidden whitespace-nowrap grow-0 shrink min-w-0" x-text="videoData.url"></div>
                        <div class="tags flex flex-wrap gap-1 min-w-0">
                            <template x-for="tag in youtubeVideoData[index].mediatags" :key="tag">
                                <div class="w-fit flex items-center text-14 whitespace-nowrap bg-yellow-20 px-3 h-6 rounded-full" x-text="tag"></div>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white"
                        x-on:click="currentYtIndex = index; showYoutubeLinkPopup = true;"
                    >Edit</button>
                    <button type="button" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white"
                        x-on:click="_removeYoutubeUrl(index)"
                    >Delete</button>
                </div>
            </div>
        </template>

    </div>

</div>
