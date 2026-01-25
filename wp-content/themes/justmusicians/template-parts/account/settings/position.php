
<div class="flex flex-col">

    <!-- Label -->
    <label for="position" class="flex gap-1">

        <span class="text-18 mb-1 inline-block">Job Title</span>

        <!-- Tooltip -->
        <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'Your position at the organization you represent. I.e. Guitarist, Talent Buyer, General Manger, etc.' ]); ?>

    </label>

    <!-- Input -->
    <input class="border border-black/20 rounded h-8 p-2" type="text" name="position" x-bind:value="accountSettings.position">

</div>
