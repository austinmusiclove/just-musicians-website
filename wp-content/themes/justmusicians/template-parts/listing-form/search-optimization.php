
<section class="flex flex-col gap-4">

<div class="flex items-center gap-2 mb-5">
    <img class="h-5" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
    <h2 class="text-25 font-bold">Search Optimization</h2>
</div>

<div>
    <div class="flex items-stretch gap-1 z-10 relative">
        <?php
            get_template_part(
                'template-parts/components/tab',
                null,
                [
                    'title'  => 'Categories',
                    'number' => 5,
                    'active' => true,
                    'required' => false
                ]
            );

            get_template_part(
                'template-parts/components/tab',
                null,
                [
                    'title'  => 'Genres',
                    'number' => null,
                    'active' => false,
                    'required' => false
                ]
            );

            get_template_part(
                'template-parts/components/tab',
                null,
                [
                    'title'  => 'Subgenres',
                    'number' => null,
                    'active' => false,
                    'required' => false
                ]
            );

            get_template_part(
                'template-parts/components/tab',
                null,
                [
                    'title'  => 'Instrumentation',
                    'number' => 3,
                    'active' => false,
                    'required' => false
                ]
            );

            get_template_part(
                'template-parts/components/tab',
                null,
                [
                    'title'  => 'Settings',
                    'number' => 1,
                    'active' => false,
                    'required' => false
                ]
            );

        ?>
    </div>
    <fieldgroup class="has-border px-4 py-8 relative h-64 relative z-0 rounded-tl-none overflow-scroll" style="margin-top: -1px">
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-y-4 gap-x-10 py-2 custom-checkbox">
    <?php
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Acoustic'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Blues'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Bluegrass'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Cover Band'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Country'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'DJ/Producer'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Electronic/EDM'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Experimental'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Folk'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Gospel Choir'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Indie'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Jazz Band'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Latin'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Metal'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Orchestra'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Pop'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Punk'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Reggae'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'R&B/Soul'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Ska'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Solo Artist'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Tribute Band'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'Wedding Band'
        ));
        echo get_template_part('template-parts/filters/elements/checkbox', '', array(
            'label' => 'World Music'
        ));
    ?>
    </div>
    </fieldgroup>
</div>

   
            
    <!-- Other Keywords -->
    <!-- Depends on tag-input-scripts.js -->
    <div>
        <h2 class="font-bold text-20 mb-2">Other Keywords</h2>
        <p class="mb-2 text-14">Did we miss anything? Add any categories, genres, subgenres, instruments, or settings that you'd like your listing to be serchable by.</p>
        <div x-data="{
            tags: keywords,
            _addTag(event)    { addTag(this, event, 'keyword-error-toast'); },
            _removeTag(index) { removeTag(this, index); },
        }">
            <input type="hidden" name="keywords[]"/>
            <div>
                <input type="text" placeholder="Type keyword and hit enter" class="w-full"
                    x-on:keydown.enter="$event.preventDefault(); _addTag($event)"
                    x-on:paste="$el.addEventListener('input', function() { _addTag($event); }, {once: true})">
            </div>

            <?php echo get_template_part('template-parts/global/toasts/error-toast', '', ['event_name' => 'keyword-error-toast']); ?>

            <div class="space-y-2">
                <!-- Display Tags -->
                <template x-for="(tag, index) in tags" :key="index + tag">
                    <div class="flex items-center bg-yellow-50 p-2 rounded-md">
                        <span x-text="tag" class="text-sm max-w-s"></span>
                        <button type="button" class="text-gray hover:text-black ml-auto" x-on:click="_removeTag(index)">
                            <span class="font-bold">X</span>
                        </button>
                        <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                    </div>
                </template>
            </div>
        </div>
    </div>


</section>