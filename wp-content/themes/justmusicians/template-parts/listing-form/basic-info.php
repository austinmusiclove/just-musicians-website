<section class="flex flex-col gap-5">

    <fieldgroup class="has-border p-4 relative">
        <!-- Performer Name -->
        <label class="hidden" for="listing_name">Performer or Band Name</label>
        <input id="performer-name-input" class="no-formatting block w-full text-20 py-2 border-b border-black/20" placeholder="Performer or band name*" type="text" id="listing_name" name="listing_name" autocomplete="off" required x-model="pName">
        <!-- Description -->
        <div class="flex gap-1 mt-2">
            <label for="description" class="flex items-center gap-1">
                Description<span class="text-red">*</span>
                <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'E.g., 5-piece Country Band' ]); ?>
            </label>
        </div>
        <textarea id="description-input" class="no-formatting block w-full h-20 mt-1" type="text" id="description" name="description" maxlength="40" required x-model="pDescription" ></textarea>
        <div class="bg-yellow-20 absolute bottom-2 right-2 text-14 text-grey px-1 py-0.5 rounded-sm"><span x-text="pDescription.length">0</span>/40 char</div>
    </fieldgroup>

    <!-- Location -->
    <div class="flex items-center gap-2">
        <h2 class="flex items-center gap-1">
            <span class="font-bold text-18">Where are you based?</span>
            <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'Enter a US or Canada postal code. This is where you consider yourself to be "based out of" not where you are from.' ]); ?>
        </h2>
        <span id="zip-active-search-spinner" class="inset-0 flex items-center justify-center htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
        </span>
    </div>

    <fieldgroup>
        <div>
            <!-- Postal Code -->
            <div class="relative">
                <img class="h-4 absolute bottom-3 left-3 opacity-60" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
                <input class="has-icon" type="text" id="pc_search" name="pc_search" autocomplete="postal-code-disabled" required placeholder="Postal Code"
                    title="Enter a US or Canada postal code (ex. 78701, A1A)."
                    x-model="zipCodeInput"
                    x-on:focus="showZipSearchOptions = true; zipCodeInput = '';"
                    x-on:click.away="showZipSearchOptions = false; $el.value = fullLocation;"
                    hx-get="<?php echo site_url('/wp-html/v1/lf-location-search-options/'); ?>"
                    hx-trigger="input changed delay:300ms"
                    hx-target="#zip-active-search-results"
                    hx-indicator="#zip-active-search-spinner"
                >
                 <div id="zip-active-search-results" x-show="showZipSearchOptions" x-cloak>
                     <?php echo get_template_part('template-parts/search/lf-location-search-state-1', '', array()); ?>
                 </div>
            </div>
            <input type="hidden" id="city" name="city" x-model="pCity">
            <input type="hidden" id="state" name="state" x-model="pState">
            <input type="hidden" id="postal_code" name="zip_code" x-model="pZipCode">
        </div>
    </fieldgroup>



    <fieldgroup class="has-border p-0">
        <!-- Ensemble Size -->
        <div class="hidden">
            <input type="hidden" name="ensemble_size[]" >
            <?php foreach (["1", "2", "3", "4", "5", "6", "7", "8", "9", "10+"] as $option) {
                echo get_template_part('template-parts/filters/elements/checkbox', '', [
                    'label' => $option,
                    'value' => $option,
                    'name' => 'ensemble_size',
                    'x-model' => 'ensembleSizeCheckboxes',
                    'is_array' => true,
                ]);
            } ?>
        </div>
        <label id="ensemble-size-input" class="block bg-yellow-10 p-2 w-full p-2 flex items-center gap-1 rounded-t-sm">
            <span class="font-bold">Ensemble size <span class="text-14 font-normal">(select all that apply)</span></span>
            <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'How many performers in your group? If you perform with different ensemble sizes, include all that apply.' ]); ?>
        </label>
        <div class="p-2 flex gap-1 items-start flex-wrap h-20" x-data="{
            sizeOptions: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10+'],
            showDropdown: false,
            addSize(size) {
                if (!ensembleSizeCheckboxes.includes(size)) { ensembleSizeCheckboxes.push(size); }
                this.showDropdown = false;
            },
            removeSize(size) {
                ensembleSizeCheckboxes = ensembleSizeCheckboxes.filter(item => item !== size);
            },
        }">
             <!-- Selected sizes -->
            <template x-for="size in ensembleSizeCheckboxes" :key="size">
                <div class="w-fit">
                    <div class="flex items-center border border-black/20 pl-3 pr-1 py-0.5 h-8 rounded-full">
                        <span class="text-14 w-fit" x-text="size"></span>
                        <button type="button" class="opacity-50 hover:opacity-100 ml-auto" x-on:click="removeSize(size)">
                            <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg'; ?>" />
                        </button>
                    </div>
                </div>
            </template>
             <!-- Add size button -->
            <div class="relative">
                <button id="addEnsembleSize" type="button" class="w-fit" x-on:click="showDropdown = true">
                    <div class="flex items-center border border-black/20 pl-3 pr-2 py-0.5 h-8 rounded-full">
                        <span class="text-14 w-fit">Add an option</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </button>
                <ul id="ensembleSizeDropdown" style="width: calc(100% - 1rem); left: .5rem" class="absolute z-10 top-full bg-white border border-black/40 rounded-md shadow-sm max-h-60 overflow-y-auto" style="margin-top: -1px"
                    x-show="showDropdown" x-cloak
                    x-on:click.away="showDropdown = false"
                >
                    <template x-for="size in sizeOptions" :key="size">
                        <li
                            @click="addSize(size)"
                            class="px-4 py-2 hover:bg-yellow-10 cursor-pointer"
                            tabindex="0"
                            @keydown.enter.prevent="addSize(size)"
                        >
                            <span x-text="size"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </fieldgroup>


    <!-- Bio -->
    <fieldgroup class="has-border p-0">
        <label class="block bg-yellow-10 p-2 w-full p-2 flex items-center gap-1 rounded-t-sm">
            <span class="font-bold">Biography</span>
        </label>
        <textarea id="bio" name="bio" placeholder="Add your bio here" class="w-full h-32 !border-0" x-model="pBio"></textarea>
    </fieldgroup>


</section>
