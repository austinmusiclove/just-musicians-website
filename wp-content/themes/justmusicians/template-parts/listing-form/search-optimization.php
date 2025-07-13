
<section class="flex flex-col gap-5">

    <div class="flex items-center gap-2">
        <img class="h-4 sm:h-5 opacity-80" src="<?php echo get_template_directory_uri() . '/lib/images/icons/search.svg'; ?>" />
        <h2 class="text-20 sm:text-25 font-bold">Search Terms</h2>
    </div>

    <div class="tabs-container relative z-0" x-data="{
        showTab1: true,
        showTab2: false,
        showTab3: false,
        showTab4: false,
        showTab5: false,
        hideTabs() {
            this.showTab1 = false;
            this.showTab2 = false;
            this.showTab3 = false;
            this.showTab4 = false;
            this.showTab5 = false;
        },
    }">
        <div class="flex flex-wrap items-stretch gap-x-1 z-10 relative">
            <?php
                get_template_part( 'template-parts/components/tab', null, [
                    'title'     => "Categories",
                    'required'  => false,
                    'show_exp'  => "showTab1",
                    'hide_exp'  => "hideTabs()",
                    'count_exp' => "categoriesCheckboxes.length > 0 ? categoriesCheckboxes.length : ''",
                ]);
                get_template_part( 'template-parts/components/tab', null, [
                    'title'     => 'Genres',
                    'required'  => false,
                    'show_exp'  => "showTab2",
                    'hide_exp'  => "hideTabs()",
                    'count_exp' => "genresCheckboxes.length > 0 ? genresCheckboxes.length : ''",
                ]);
                get_template_part( 'template-parts/components/tab', null, [
                    'title'     => 'Subgenres',
                    'required'  => false,
                    'show_exp'  => "showTab3",
                    'hide_exp'  => "hideTabs()",
                    'count_exp' => "subgenresCheckboxes.length > 0 ? subgenresCheckboxes.length : ''",
                ]);
                get_template_part( 'template-parts/components/tab', null, [
                    'title'     => 'Instruments',
                    'required'  => false,
                    'show_exp'  => "showTab4",
                    'hide_exp'  => "hideTabs()",
                    'count_exp' => "instCheckboxes.length > 0 ? instCheckboxes.length : ''",
                ]);
                get_template_part( 'template-parts/components/tab', null, [
                    'title'     => 'Settings',
                    'required'  => false,
                    'show_exp'  => "showTab5",
                    'hide_exp'  => "hideTabs()",
                    'count_exp' => "settingsCheckboxes.length > 0 ? settingsCheckboxes.length : ''",
                ]);
            ?>
        </div>
        <fieldgroup class="has-border px-4 py-8 relative h-[17rem] relative z-0 rounded-tl-none overflow-scroll" style="margin-top: -1px">
            <div class="overflow-hidden" x-show="showTab1" x-cloak>
                <?php echo get_template_part('template-parts/filters/taxonomy-options', '', [
                    'terms'           => $args['categories'],
                    'input_name'      => 'categories',
                    'input_x_model'   => 'categoriesCheckboxes',
                    'show_search_bar' => true,
                ]); ?>
            </div>
            <div class="overflow-hidden" x-show="showTab2" x-cloak>
                <?php echo get_template_part('template-parts/filters/taxonomy-options', '', [
                    'terms'           => $args['genres'],
                    'input_name'      => 'genres',
                    'input_x_model'   => 'genresCheckboxes',
                    'show_search_bar' => true,
                    'max_options'     => 6,
                ]); ?>
            </div>
            <div class="overflow-hidden" x-show="showTab3" x-cloak>
                <?php echo get_template_part('template-parts/filters/taxonomy-options', '', [
                    'terms'           => $args['subgenres'],
                    'input_name'      => 'subgenres',
                    'input_x_model'   => 'subgenresCheckboxes',
                    'show_search_bar' => true,
                ]); ?>
            </div>
            <div class="overflow-hidden" x-show="showTab4" x-cloak>
                <?php echo get_template_part('template-parts/filters/taxonomy-options', '', [
                    'terms'           => $args['instrumentations'],
                    'input_name'      => 'instrumentations',
                    'input_x_model'   => 'instCheckboxes',
                    'show_search_bar' => true,
                ]); ?>
            </div>
            <div class="overflow-hidden" x-show="showTab5" x-cloak>
                <?php echo get_template_part('template-parts/filters/taxonomy-options', '', [
                    'terms'           => $args['settings'],
                    'input_name'      => 'settings',
                    'input_x_model'   => 'settingsCheckboxes',
                    'show_search_bar' => true,
                ]); ?>
            </div>
        </fieldgroup>
    </div>



    <!-- Other Keywords -->
    <!-- Depends on tag-input-scripts.js -->
    <div>
        <h2 class="font-bold text-20 mb-2">Other Keywords</h2>
        <p class="mb-4 text-14">Did we miss anything? Add any categories, genres, subgenres, instruments, settings, or other keywords that you'd like your listing to be serchable by.</p>
        <div x-data="{
            tags: keywords,
            _addTag(input, value) { addTag(this, input, value, 'error-toast'); },
            _removeTag(index)     { removeTag(this, index); },
        }">
            <input type="hidden" name="keywords[]"/>
            <div class="relative">
                <input type="text" placeholder="Type your keyword" class="w-full !pr-16"
                    x-ref="keywordsInput"
                    x-on:keydown.enter="$event.preventDefault(); _addTag($refs.keywordsInput, $refs.keywordsInput.value)"
                    x-on:paste="$el.addEventListener('input', function() { _addTag($refs.keywordsInput, $refs.keywordsInput.value); }, {once: true})">
                <button type="button" class="absolute top-2 right-2 w-fit rounded text-12 border border-black/40 group flex items-center font-bold py-1 px-2 hover:border-black text-grey hover:text-black"
                    x-on:click="_addTag($refs.keywordsInput, $refs.keywordsInput.value)"
                >Add +</button>
            </div>

            <div class="gap-1 mt-4 flex flex-wrap gap-2">
                <!-- Alpine template w/classes of tags above -->
                <template class="w-fit" x-for="(tag, index) in tags" :key="index">
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
