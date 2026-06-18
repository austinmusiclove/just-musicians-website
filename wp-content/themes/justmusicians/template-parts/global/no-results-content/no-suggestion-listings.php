<div class="font-sun-motter text-center px-4 pb-16 pt-12 sm:py-20 relative flex items-center justify-center flex-col">

    <div class="pb-32 relative z-10">
        <span class="text-18 sm:text-22 block text-center mb-4">Looks like we couldn't find any suggestions.</span>
        <p class="text-20 mb-4">Start a search to find musicians to invite to respond to this inquiry.</p>

        <button type="reset" class="bg-yellow shadow-black-offset border-2 border-black font-sun-motter text-16 px-5 py-3"
            x-on:click="window.location = '/'">
            Start a Search
        </button>
    </div>

    <img class="w-40 absolute bottom-0 left-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/cactus.svg'; ?>" />
    <img class="w-40 absolute bottom-0 right-0 z-0" src="<?php echo get_template_directory_uri() . '/lib/images/other/tumbleweed.svg'; ?>" />

</div>
