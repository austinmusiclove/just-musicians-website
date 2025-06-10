<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('venues');
    const dropdown = document.getElementById('venue-dropdown');

    input.addEventListener('focus', () => {
        dropdown.classList.remove('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});
</script>


<section class="flex flex-col gap-5">

<div class="flex items-center gap-2">
    <img class="h-6 opacity-80" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
    <h2 class="text-25 font-bold">Venues Played</h2>
</div>

<div class="relative z-0">    
    <!-- Venues Played -->
    <!-- Depends on tag-input-scripts.js -->
    <div>
        <div x-data="{
            tags: keywords,
            _addTag(event)    { addTag(this, event, 'keyword-error-toast'); },
            _removeTag(index) { removeTag(this, index); },
        }">
        <div class="relative">
            <input type="hidden" name="keywords[]"/>
            <div class="relative">
                <input type="text" id="venues" placeholder="Search for venues" class="w-full"
                    x-on:keydown.enter="$event.preventDefault(); _addTag($event)"
                    x-on:paste="$el.addEventListener('input', function() { _addTag($event); }, {once: true})">

                    <button class="absolute top-3 right-3 opacity-50 hover:opacity-100" x-ref="submitButton">
                        <img class="h-4" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
                    </button>
            </div>
            <ul id="venue-dropdown" class="absolute z-10 top-full left-0 w-full bg-white border border-black/40 rounded-md shadow-sm max-h-32 overflow-y-scroll hidden" style="margin-top: -1px">
                <li class="p-2 hover:bg-yellow-10 cursor-pointer">Victory East <span class="opacity-50">| 1104 E. 11th St, Austin, Texas</span></li>
                <li class="p-2 hover:bg-yellow-10 cursor-pointer">Vito’s Vault <span class="opacity-50">| 5901 W Lawrence Ave, Chicago, IL 60630</span></li>
            </ul>
        </div>
            

            <?php echo get_template_part('template-parts/global/toasts/error-toast', '', ['event_name' => 'keyword-error-toast']); ?>


            <div class="gap-1 mt-4 flex flex-wrap gap-2">
                <!-- Tag 1 -->
                <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                    <div class="flex items-start bg-yellow-50 pl-2 py-1 pr-8 relative rounded-md">
                        <div class="text-14 w-fit">
                            <span class="font-bold">Skylark Lounge</span><br />
                            2039 Airport Blvd<br />
                            Austin, TX 78722
                        </div>
                        <button type="button" class="absolute top-0 right-0 opacity-50 hover:opacity-100" x-on:click="_removeTag(index)">
                            <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg'; ?>" />
                        </button>
                        <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                    </div>
                </div>
                <!-- Tag 2 -->
                <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                    <div class="flex items-start bg-yellow-50 pl-2 py-1 pr-8 relative rounded-md">
                        <div class="text-14 w-fit">
                            <span class="font-bold">Emo's Austin</span><br />
                            2015 E Riverside Dr<br />
                            Austin, TX 78741
                        </div>
                        <button type="button" class="absolute top-0 right-0 opacity-50 hover:opacity-100" x-on:click="_removeTag(index)">
                            <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg'; ?>" />
                        </button>
                        <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                    </div>
                </div>
                <!-- Alpine template w/classes of tags above -->
                <template class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                    <div class="flex items-start bg-yellow-50 pl-2 py-1 pr-8 relative rounded-md">
                        <div x-text="tag" class="text-14 w-fit">
                            <span class="font-bold">Emo's Austin</span><br />
                                2015 E Riverside Dr<br />
                                Austin, TX 78741
                        </div>
                        <button type="button" class="absolute top-0 right-0 opacity-50 hover:opacity-100" x-on:click="_removeTag(index)">
                            <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg'; ?>" />
                        </button>
                        <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                    </div>
                </template>

            </div>
        </div>
    </div>


</section>