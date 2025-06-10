<div data-media-tab="cover-image" class="hidden flex flex-col h-full p-2 overflow-hidden">
    
<div class="relative -mx-2 sm:mx-0 pt-1">
    <h3 class="font-bold text-18">Cover Image</h3>
</div>

<!-- State 1 -->
<div data-parent-tab="cover" data-screen="1" class="flex flex-col items-center justify-center grow text-center px-8">
    <div>This field is for the large image that appears at the top of your profile page.</div>
    <button data-show="cover-2" class="w-fit rounded text-14 mt-4 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Upload +</button>
</div>

<!-- State 2 -->
<div data-parent-tab="cover" data-screen="3" class="hidden overflow-hidden flex items-center justify-center grow mt-4 pb-2">
    <div class="grid grid-cols-2 gap-2">
        <div>
            <div class="aspect-4/3 w-full max-w-full max-h-full">
                <img class="h-full object-cover" src="<?php echo get_template_directory_uri() . '/lib/images/placeholder/indoor-creature.jpg' ;?>">
            </div>
        </div>
        <div class="flex items-center flex-col justify-center gap-4 px-4">
            <!--<div class="text-center text-14">The large image that appears at the top of your profile page.</div>-->
            <div class="text-center text-grey">indoor-creature.jpg</div>
            <div class="flex gap-2">
                <button data-show="cover-2" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Edit</button>
                <button data-show="cover-1" class="w-fit rounded text-14 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black disabled:bg-grey disabled:text-white">Delete</button>
            </div>
        </div>
    </div>
</div>


</div>