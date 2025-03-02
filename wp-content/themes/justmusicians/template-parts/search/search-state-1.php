<div data-search-state="desktop-1" class="absolute top-full left-0 w-full px-4 py-4 bg-white flex flex-col shadow-md rounded-sm">
    <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="#" x-on:click="document.getElementById('clear-form').click(); $nextTick(() => { document.getElementById('typesBandCheckbox').click() });">
        <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-bands.svg'; ?>" />
        Bands
    </a>
    <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="#" x-on:click="document.getElementById('clear-form').click(); $nextTick(() => { document.getElementById('typesMusicianCheckbox').click() });">
        <img class="h-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-person.svg'; ?>" />
        Musicians
    </a>
    <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="#" x-on:click="document.getElementById('clear-form').click(); $nextTick(() => { document.getElementById('typesDJCheckbox').click() });">
        <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-djs.svg'; ?>" />
        DJs
    </a>
    <a class="px-2 py-1.5 flex items-center gap-2 hover:bg-navy/10 rounded-sm" href="#" x-on:click="document.getElementById('clear-form').click(); $nextTick(() => { document.getElementById('tagsWeddingBandCheckbox').click() });">
        <img class="w-4 opacity-40" src="<?php echo get_template_directory_uri() . '/lib/images/icons/icon-wedding.svg'; ?>" />
        Wedding Music
    </a>
</div>
