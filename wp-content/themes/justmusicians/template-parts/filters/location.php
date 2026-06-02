<div class="border-b border-black/20 mb-6 pb-6 last:mb-0 last:pb-0 last:border-b-0">
    <h3 class="font-bold text-18 mb-3">Location</h3>
    <form class="flex flex-col gap-y-2">
        <div class="relative flex items-center">
            <img class="h-4 absolute left-2" src="<?php echo get_template_directory_uri() . '/lib/images/icons/location.svg'; ?>" />
            <input class="w-full py-2 pr-3 pl-5 no-formatting border border-black/20 focus:border-black outline-none" type="text" name="location" placeholder="Enter city or postal code" />
        </div>
        <label for="radius" class="hidden">Radius</label>
        <select id="radius" name="radius">
            <option value="" disabled selected hidden>Radius</option>
            <option value="10">5 miles</option>
            <option value="15">15 miles</option>
            <option value="25" selected>25 miles</option>
            <option value="40">40 miles</option>
        </select>
    </form>
</div>
