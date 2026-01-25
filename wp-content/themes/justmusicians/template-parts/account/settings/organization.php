
<div class="flex flex-col">

    <!-- Label -->
    <label for="organization" class="flex gap-1">

        <span class="text-18 mb-1 inline-block">Organization</span>

        <!-- Tooltip -->
        <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'The organization you represent. This could be your band name, agency name, venue, etc.' ]); ?>

    </label>

    <!-- Input -->
    <input class="border border-black/20 rounded h-8 p-2" type="text" name="organization" x-bind:value="accountSettings.organization">

</div>
