<section class="flex flex-col gap-5">

    <fieldgroup class="has-border p-4 relative">
        <!-- Performer Name -->
        <label class="hidden" for="listing_name">Performer or Band Name</label>
        <input id="performer-name-input" class="no-formatting block w-full text-20 py-2 border-b border-black/20" placeholder="Performer or band name*" type="text" id="listing_name" name="listing_name" autocomplete="off" required x-model="pName">
        <!-- Description -->
        <div class="flex gap-1 mt-2">
            <label for="description" class="flex items-center gap-1">
                Description<span class="text-red">*</span>
                <?php echo get_template_part('template-parts/global/tooltips/tooltip', '', [ 'tooltip' => 'E.g., 5-piece Country Band' ]); ?>
            </label>
        </div>
        <textarea id="description-input" class="no-formatting block w-full h-20 mt-1" type="text" id="description" name="description" maxlength="40" required x-model="pDescription" ></textarea>
        <div class="bg-yellow-20 absolute bottom-2 right-2 text-14 text-grey px-1 py-0.5 rounded-sm"><span x-text="pDescription.length">0</span>/40 char</div>
    </fieldgroup>

    <!-- Location -->
    <div class="flex items-center gap-2">
        <h2 class="flex items-center gap-1">
            <span class="font-bold text-18">Where are you based?</span>
            <?php echo get_template_part('template-parts/global/tooltips/tooltip', '', [ 'tooltip' => 'Enter a US or Canada postal code. This is where you consider yourself to be "based out of" not where you are from.' ]); ?>
        </h2>
        <span id="zip-active-search-spinner" class="inset-0 flex items-center justify-center htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'yellow']); ?>
        </span>
    </div>

    <fieldgroup>
        <div>
            <!-- Postal Code -->
            <?php echo get_template_part('template-parts/search/active-search/location-search-input', '', [
                'container_class' => 'relative flex items-center',
                'image_class'     => 'h-4 absolute bottom-3 left-3 opacity-60',
                'image_file'      => 'location-2.svg',
                'id'              => 'listing-form-zip',
                'input_class'     => 'has-icon',
                'input_name'      => 'pc_search',
                'placeholder'     => 'Postal Code',
                'autocomplete'    => 'postal-code-disabled',
                'required'        => true,
                'input_var'       => 'zipCodeInput',
                'selected_var'    => 'fullLocation',
                'show_var'        => 'showZipSearchOptions',
                'htmx_path'       => '/wp-html/v1/location-search-options-pc/',
                'spinner_id'      => 'zip-active-search-spinner',
                'update_func'     => 'listingFormUpdateLocation',
                'state_1_msg'     => 'Enter a US or Canada postal code (ex. 78701, A1A)',
            ]); ?>
            <input type="hidden" id="city" name="city" x-model="pCity">
            <input type="hidden" id="state" name="state" x-model="pState">
            <input type="hidden" id="postal_code" name="zip_code" x-model="pZipCode">
        </div>
    </fieldgroup>

    <?php echo get_template_part('template-parts/global/form/tag-select-input', '', [
        'id'         => 'ensemble-size-input',
        'label'      => 'Ensemble Size',
        'input_name' => 'ensemble_size',
        'options'    => ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10+"],
        'x-model'    => 'ensembleSizeCheckboxes',
        'tooltip'    => "How many performers in your group? If you perform with different ensemble sizes, include all that apply.",
    ]); ?>

    <!-- Bio -->
    <fieldgroup class="has-border p-0">
        <label class="block bg-yellow-10 p-2 w-full p-2 flex items-center gap-1 rounded-t-sm">
            <span class="font-bold">Biography</span>
        </label>
        <textarea id="bio" name="bio" placeholder="Add your bio here" class="w-full h-32 !border-0" x-model="pBio"></textarea>
    </fieldgroup>


</section>
