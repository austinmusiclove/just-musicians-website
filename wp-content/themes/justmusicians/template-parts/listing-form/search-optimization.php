
<section class="flex flex-col gap-5">

    <div class="flex items-center gap-2">
        <img class="h-4 sm:h-5 opacity-80" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
        <h2 class="text-20 sm:text-25 font-bold">Search Optimization</h2>
    </div>

    <div class="tabs-container relative z-0">
        <div class="flex flex-wrap items-stretch gap-x-1 z-10 relative">
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
        <p class="mb-4 text-14">Did we miss anything? Add any categories, genres, subgenres, instruments, or settings that you'd like your listing to be serchable by.</p>
        <div x-data="{
            tags: keywords,
            _addTag(event)    { addTag(this, event, 'keyword-error-toast'); },
            _removeTag(index) { removeTag(this, index); },
        }">
            <input type="hidden" name="keywords[]"/>
            <div class="relative">
                <input type="text" placeholder="Type your keyword" class="w-full"
                    x-on:keydown.enter="$event.preventDefault(); _addTag($event)"
                    x-on:paste="$el.addEventListener('input', function() { _addTag($event); }, {once: true})">
                    <button class="absolute top-2 right-2 w-fit rounded text-12 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black text-grey hover:text-black disabled:bg-grey disabled:text-white" x-ref="submitButton">Add +</button>
            </div>

            <?php echo get_template_part('template-parts/global/toasts/error-toast', '', ['event_name' => 'keyword-error-toast']); ?>


            <div class="gap-1 mt-4 flex flex-wrap gap-2">
                <!-- Tag 1 -->
                <!--
                <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                    <div class="flex items-center bg-yellow-50 pl-3 pr-1 py-0.5 rounded-full">
                        <span class="text-14 w-fit">live looper</span>
                        <button type="button" class="opacity-50 hover:opacity-100 ml-auto" x-on:click="_removeTag(index)">
                            <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg'; ?>" />
                        </button>
                        <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                    </div>
                </div>
                -->
                <!-- Tag 2 -->
                <!--
                <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                    <div class="flex items-center bg-yellow-50 pl-3 pr-1 py-0.5 rounded-full">
                        <span class="text-14 w-fit">psychedelic steam punk</span>
                        <button type="button" class="opacity-50 hover:opacity-100 ml-auto" x-on:click="_removeTag(index)">
                            <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg'; ?>" />
                        </button>
                        <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                    </div>
                </div>
                -->
                <!-- Alpine template w/classes of tags above -->
                <template class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                    <div class="flex items-center bg-yellow-50 pl-3 pr-1 py-0.5 rounded-full">
                        <span x-text="tag" class="text-14 w-fit"></span>
                        <button type="button" class="opacity-50 hover:opacity-100 ml-auto" x-on:click="_removeTag(index)">
                            <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg'; ?>" />
                        </button>
                        <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                    </div>
                </template>

            </div>
        </div>
    </div>


</section>
