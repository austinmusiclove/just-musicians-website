
<div class="flex flex-col">

    <!-- Label -->
    <label for="position" class="flex">

        <span class="text-18 mb-1 inline-block">Position</span>


        <!-- Tooltip -->
        <div class="group relative px-2">
            <div class="tooltip text-white bg-black px-4 py-3 text-14 rounded hidden group-hover:block absolute z-50 w-56 -top-[100px] -right-28 md:right-auto">
                Your position at the organization you represent. I.e. Guitarist, Talent Buyer, General Manger, etc.
            </div>
            <img class="opacity-40 h-4 cursor-pointer hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>"/>
        </div>

    </label>

    <!-- Input -->
    <input class="border border-black/20 rounded h-8 p-2" type="text" name="position" x-bind:value="accountSettings.position">

</div>
