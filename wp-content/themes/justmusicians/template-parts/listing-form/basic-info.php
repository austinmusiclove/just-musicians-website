<script>
    document.addEventListener('DOMContentLoaded', function () {

        const states = [
            'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware',
            'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky',
            'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi',
            'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico',
            'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania',
            'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
            'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
        ];

        const toggleBtn = document.getElementById('dropdownToggle');
        const dropdown = document.getElementById('dropdown');
        const hiddenInput = document.getElementById('state');

        // Inject list items
        states.forEach(state => {
            const li = document.createElement('li');
            li.textContent = state;
            li.className = 'px-4 py-2 hover:bg-yellow-10 cursor-pointer';
            li.addEventListener('click', () => {
            toggleBtn.innerHTML = `${state} <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>`;
            hiddenInput.value = state;
            dropdown.classList.add('hidden');
            });
            dropdown.appendChild(li);
        });

        // Toggle dropdown
        toggleBtn.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (!document.getElementById('stateDropdown').contains(e.target)) {
            menu.classList.add('hidden');
            }
        });
    }); 
</script>


<section class="flex flex-col gap-4">


<fieldgroup class="has-border p-4 relative">
    <!-- Performer Name -->
    <label class="hidden" for="listing_name">Performer or Band Name</label>
    <input class="no-formatting block w-full text-20 py-2 border-b border-black/20" placeholder="Performer or band name*" type="text" id="listing_name" name="listing_name" autocomplete="off" required x-model="pName">
    <!-- Description -->
    <div class="flex gap-1 mt-2">
        <label for="description">Description<span class="text-red">*</span></label>
    </div>
    <!--
    <span class="tooltip">
        i<span class="tooltip-text">Examples: Psych rock band, Cello player, 90s cover band</span>
    </span><br>
    -->
    <textarea class="no-formatting block w-full h-20 mt-1" type="text" id="description" name="description" maxlength="35" placeholder="E.g., 5-piece Country Band" required x-model="pDescription" ></textarea>
    <div class="bg-yellow-20 absolute bottom-2 right-2 text-14 text-grey px-1 py-0.5 rounded-sm">0/40 char</div>
</fieldgroup>

<h2 class="font-bold text-18">Where are you based?</h2>

<fieldgroup>
    <div class="grid grid-cols-3 gap-2">
        <!-- City -->
        <div class="relative">
            <label for="city" class="mb-1 inline-block">City<span class="text-red">*</span></label>
            <img class="h-4 absolute bottom-3    left-3" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location-2.svg'; ?>" />
            <input class="has-icon" type="text" id="city" name="city" required x-model="pCity">
        </div>
        <!-- State -->
        <div>
            <label for="state" class="mb-1 inline-block">State<span class="text-red">*</span></label><br>
            <div class="relative flex items-center justify-between" id="stateDropdown">
                <button id="dropdownToggle" type="button" class="inline-flex justify-between items-center grow px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-black/40 rounded-md focus:outline-none">
                Select one
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                </button>

                <ul id="dropdown" class="absolute z-10 top-full left-0 w-full bg-white border border-black/40 rounded-md shadow-sm max-h-60 overflow-y-auto hidden" style="margin-top: -1px">
                </ul>

                <!-- Hidden input to store selected state -->
                <input type="hidden" id="state" name="state" required x-model="pState">
            </div>
        </div>
        <!-- Zip Code -->
        <div>
            <label for="zip_code" class="mb-1 inline-block">Zip<span class="text-red">*</span></label>
            <!--
            <span class="tooltip">
                i<span class="tooltip-text">This will be used to help match buyers with musicians who are broadly geographically near by.</span>
            </span><br>
            -->
            <input type="text" id="zip_code" name="zip_code"
                pattern="^\d{5}(-\d{4})?$" maxlength="5"
                title="Enter a valid ZIP code (e.g., 12345 or 12345-6789)."
                <?php if ($listing_data) { echo 'value="' . $listing_data['zip_code'] . '"'; } ?>>
        </div>
    </div>
</fieldgroup>



<fieldgroup class="has-border p-0">
    <!-- Ensemble Size -->
    <label class="font-bold bg-yellow-10 p-2 w-full">Ensemble size</label>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 gap-y-2 gap-x-10 custom-checkbox overflow-scroll max-h-[500px] md:max-h-[240px]">
        <input type="hidden" name="ensemble_size[]" >
        <?php $ensemble_size_options = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10+"];
        foreach ($ensemble_size_options as $option) {
            echo get_template_part('template-parts/filters/elements/checkbox', '', [
                'label' => $option,
                'value' => $option,
                'name' => 'ensemble_size',
                'x-model' => 'ensembleSizeCheckboxes',
                'is_array' => true,
            ]);
        } ?>
    </div>
</fieldgroup>


<!-- Bio -->
<div>
    <label class="hidden" for="bio">Bio</label>
    <textarea id="bio" name="bio" placeholder="Biography" class="w-full h-32"><?php if ($listing_data) { echo $listing_data['bio']; } ?></textarea>
</div>


</section>