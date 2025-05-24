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

    const sizes = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10+'];

    const toggleBtn = document.getElementById('dropdownToggle');
    const stateDropdown = document.getElementById('stateDropdown');
    const hiddenInput = document.getElementById('state');

    const addEnsembleSizeBtn = document.getElementById('addEnsembleSize');
    const ensembleSizeDropdown = document.getElementById('ensembleSizeDropdown');

    // Inject state list items
    states.forEach(state => {
        const li = document.createElement('li');
        li.textContent = state;
        li.className = 'px-4 py-2 hover:bg-yellow-10 cursor-pointer';
        li.addEventListener('click', () => {
            toggleBtn.innerHTML = `${state} <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>`;
            hiddenInput.value = state;
            stateDropdown.classList.add('hidden');
        });
        stateDropdown.appendChild(li);
    });

    // Inject size list items
    sizes.forEach(size => {
        const li = document.createElement('li');
        li.textContent = size;
        li.className = 'px-4 py-2 hover:bg-yellow-10 cursor-pointer';
        ensembleSizeDropdown.appendChild(li);
    });

    // Toggle dropdowns
    addEnsembleSizeBtn.addEventListener('click', () => {
        ensembleSizeDropdown.classList.toggle('hidden');
    });
    toggleBtn.addEventListener('click', () => {
        stateDropdown.classList.toggle('hidden');
    });

    // Close dropdown on outside click
    document.addEventListener('click', (e) => {
        if (!stateDropdown.contains(e.target) && !toggleBtn.contains(e.target)) {
            stateDropdown.classList.add('hidden');
        }
        if (!ensembleSizeDropdown.contains(e.target) && !addEnsembleSizeBtn.contains(e.target)) {
            ensembleSizeDropdown.classList.add('hidden');
        }
    });
});
</script>



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
                <div id="tooltip-sort" class="tooltip text-white bg-black px-4 py-3 text-14 rounded hidden group-hover:block absolute z-50 w-56 -top-[45px] -right-28 md:right-auto">
                E.g., 5-piece Country Band
                </div>
                <img id="info-sort" class="opacity-40 h-4 cursor-pointer hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>"/>
            </div>
        </label>
    </div>
    <!--
    <span class="tooltip">
        i<span class="tooltip-text">Examples: Psych rock band, Cello player, 90s cover band</span>
    </span><br>
    -->
    <textarea class="no-formatting block w-full h-20 mt-1" type="text" id="description" name="description" maxlength="35" required x-model="pDescription" ></textarea>
    <div class="bg-yellow-20 absolute bottom-2 right-2 text-14 text-grey px-1 py-0.5 rounded-sm">0/40 char</div>
</fieldgroup>

<h2 class="flex items-center gap-1">
    <span class="font-bold text-18">Where are you based?</span>
    <div class="group relative">
        <div id="tooltip-sort" class="tooltip text-white bg-black px-4 py-3 text-14 rounded hidden group-hover:block absolute z-50 w-56 -top-[45px] -right-28 md:right-auto">
        Lorem ipsum dolor sit amet.
        </div>
        <img id="info-sort" class="opacity-40 h-4 cursor-pointer hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>"/>
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
        <div>
            <label for="state" class="mb-1 inline-block">State<span class="text-red">*</span></label><br>
            <div class="relative flex items-center justify-between">
                <button id="dropdownToggle" type="button" class="inline-flex justify-between items-center grow px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-black/40 rounded-md focus:outline-none">
                Select one
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                </button>

                <ul id="stateDropdown" class="absolute z-10 top-full left-0 w-full bg-white border border-black/40 rounded-md shadow-sm max-h-60 overflow-y-auto hidden" style="margin-top: -1px"></ul>

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
    <label class="block bg-yellow-10 p-2 w-full p-2 flex items-center gap-1 rounded-t-sm">
        <span class="font-bold">Ensemble size</span>
        <div class="group relative">
            <div id="tooltip-sort" class="tooltip text-white bg-black px-4 py-3 text-14 rounded hidden group-hover:block absolute z-50 w-56 -top-[45px] -right-28 md:right-auto">
            Lorem ipsum dolor sit amet.
            </div>
            <img id="info-sort" class="opacity-40 h-4 cursor-pointer hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>"/>
        </div>
    </label>
    <div class="p-2 flex gap-1 items-start flex-wrap h-20">
        <!-- Tag 1 -->
        <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
            <div class="flex items-center border border-black/20 pl-3 pr-1 py-0.5 h-8 rounded-full">
                <span class="text-14 w-fit">7</span>
                <button type="button" class="opacity-50 hover:opacity-100 ml-auto" x-on:click="_removeTag(index)">
                    <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg'; ?>" />
                </button>
                <input type="hidden" name="keywords[]" x-bind:value="tag"/>
            </div>
        </div>  
        <!-- Tag 2 -->
        <div class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
            <div class="flex items-center border border-black/20 pl-3 pr-1 py-0.5 h-8 rounded-full">
                <span class="text-14 w-fit">10+</span>
                <button type="button" class="opacity-50 hover:opacity-100 ml-auto" x-on:click="_removeTag(index)">
                    <img class="h-6" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg'; ?>" />
                </button>
                <input type="hidden" name="keywords[]" x-bind:value="tag"/>
            </div>
        </div>  
         <!-- Add size button -->
        <div class="relative">
            <button id="addEnsembleSize" class="w-fit" x-for="(tag, index) in tags" :key="index + tag">
                <div class="flex items-center border border-black/20 pl-3 pr-2 py-0.5 h-8 rounded-full">
                    <span class="text-14 w-fit">Add an option</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    <input type="hidden" name="keywords[]" x-bind:value="tag"/>
                </div>
            </button>  
            <ul id="ensembleSizeDropdown" style="width: calc(100% - 1rem); left: .5rem" class="absolute z-10 top-full bg-white border border-black/40 rounded-md shadow-sm max-h-60 overflow-y-auto hidden" style="margin-top: -1px"></ul>
        </div>
    </div>
</fieldgroup>


<!-- Bio -->
<div>
    <label class="hidden" for="bio">Bio</label>
    <textarea id="bio" name="bio" placeholder="Biography" class="w-full h-32"><?php if ($listing_data) { echo $listing_data['bio']; } ?></textarea>
</div>


</section>