<div class="border-b border-black/20 mb-6 pb-6 last:mb-0 last:pb-0 last:border-b-0">
    <h3 class="font-bold text-18 mb-3">Location</h3>
    <form class="flex flex-col gap-y-2">
        <label for="zipcode" class="hidden">Enter zipcode</label>
        <input id="zipcode" type="number" name="zipcode" placeholder="Enter zip code" />
        <label for="radius" class="hidden">Radius</label>
        <select id="radius" name="radius">
            <option value="" disabled selected hidden>Radius</option>
            <option value="10 miles">10 miles</option>
            <option value="20 miles">20 miles</option>
            <option value="30 miles">30 miles</option>
        </select>
    </form>
</div>