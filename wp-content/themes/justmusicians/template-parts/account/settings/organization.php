
<div class="flex flex-col">

    <!-- Label -->
    <label for="organization" class="flex">

        <span class="text-18 mb-1 inline-block">Organization</span>


        <!-- Tooltip -->
        <div class="group relative px-2">
            <div class="tooltip text-white bg-black px-4 py-3 text-14 rounded hidden group-hover:block absolute z-50 w-56 -top-[100px] -right-28 md:right-auto">
                The organization you represent. This could be your band name, agency name, venue, etc.
            </div>
            <img class="opacity-40 h-4 cursor-pointer hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/circle-info.svg'; ?>"/>
        </div>

    </label>

    <!-- Input -->
    <input class="border border-black/20 rounded h-8 p-2" type="text" name="organization" x-bind:value="accountSettings.organization">

</div>
