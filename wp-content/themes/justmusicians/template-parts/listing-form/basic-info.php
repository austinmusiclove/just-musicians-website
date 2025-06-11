<section class="flex flex-col gap-5">

    <fieldgroup class="has-border p-4 relative">
        <!-- Performer Name -->
        <label class="hidden" for="listing_name">Performer or Band Name</label>
        <input class="no-formatting block w-full text-20 py-2 border-b border-black/20" placeholder="Performer or band name*" type="text" id="listing_name" name="listing_name" autocomplete="off" required x-model="pName">
        <!-- Description -->
        <div class="flex gap-1 mt-2">
            <label for="description" class="flex items-center gap-1">
                Description<span class="text-red">*</span>
                <div class="group relative">
                    <div class="tooltip text-white bg-black px-4 py-3 text-14 rounded hidden group-hover:block absolute z-50 w-56 -top-[45px] -right-28 md:right-auto">
                    E.g., 5-piece Country Band
                    </div>
                    <img class="opacity-40 h-4 cursor-pointer hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>"/>
                </div>
            </label>
        </div>
        <textarea class="no-formatting block w-full h-20 mt-1" type="text" id="description" name="description" maxlength="40" required x-model="pDescription" ></textarea>
        <div class="bg-yellow-20 absolute bottom-2 right-2 text-14 text-grey px-1 py-0.5 rounded-sm"><span x-text="pDescription.length">0</span>/40 char</div>
    </fieldgroup>

    <h2 class="flex items-center gap-1">
        <span class="font-bold text-18">Where are you based?</span>
        <div class="group relative">
            <div class="tooltip text-white bg-black px-4 py-3 text-14 rounded hidden group-hover:block absolute z-50 w-56 -top-[82px] -right-28 md:right-auto">
            This is where you consider yourself to be "based out of" not where you are from
            </div>
            <img class="opacity-40 h-4 cursor-pointer hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>"/>
        </div>

    </h2>

    <fieldgroup>
        <div class="grid sm:grid-cols-3 gap-2">
            <!-- City -->
            <div class="relative">
                <label for="city" class="mb-1 inline-block">City<span class="text-red">*</span></label>
                <img class="h-4 absolute bottom-3 left-3 opacity-60" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
                <input class="has-icon" type="text" id="city" name="city" required x-model="pCity">
            </div>
            <!-- State -->
            <div x-data="{
                states: [
                    'Texas', 'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware',
                    'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky',
                    'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi',
                    'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico',
                    'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania',
                    'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
                    'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
                ],
                showDropdown: false,
                select(state) {
                    this.showDropdown = false;
                    pState = state;
                }
            }">
                <label for="state" class="mb-1 inline-block">State</label><br>
                <div class="relative flex items-center justify-between">
                    <button type="button" class="inline-flex justify-between items-center grow px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-black/40 rounded-md focus:outline-none"
                        x-on:click="showDropdown = true"
                    >
                    <span x-text="pState || 'Select one'"></span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    </button>

                    <ul class="absolute z-10 top-full left-0 w-full bg-white border border-black/40 rounded-md shadow-sm max-h-60 overflow-y-auto" style="margin-top: -1px"
                        x-show="showDropdown" x-cloak
                        x-on:click.away="showDropdown = false"
                    >
                        <template x-for="(state, index) in states" :key="index">
                            <li
                                @click="select(state)"
                                class="px-4 py-2 hover:bg-yellow-10 cursor-pointer"
                                tabindex="0"
                                @keydown.enter.prevent="select(state)"
                            >
                                <span x-text="state"></span>
                            </li>
                        </template>
                    </ul>

                    <!-- Hidden input to store selected state -->
                    <input type="hidden" name="state" required x-model="pState">
                </div>
            </div>
            <!-- Zip Code -->
            <div>
                <label for="zip_code" class="mb-1 inline-block">Zip<span class="text-red">*</span></label>
                <input type="text" id="zip_code" name="zip_code" required
                    pattern="^\d{5}(-\d{4})?$" maxlength="5"
                    title="Enter a valid ZIP code (e.g., 12345 or 12345-6789)."
                    x-model="pZipCode"
                >
            </div>
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
        <label class="block bg-yellow-10 p-2 w-full p-2 flex items-center gap-1 rounded-t-sm">
            <span class="font-bold">Ensemble size</span>
            <div class="group relative">
                <div class="tooltip text-white bg-black px-4 py-3 text-14 rounded hidden group-hover:block absolute z-50 w-56 -top-[100px] -right-28 md:right-auto">
                How many performers in your group? If you perform with different ensemble sizes, include all that apply.
                </div>
                <img class="opacity-40 h-4 cursor-pointer hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>"/>
            </div>
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
