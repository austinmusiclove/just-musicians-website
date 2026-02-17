
<div class="flex flex-col">

    <!-- Label -->
    <label for="display_name" class="flex gap-1">

        <span class="text-18 mb-1 inline-block">Display Name <span class="text-red">*</span></span>

        <!-- Tooltip -->
        <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'This is the name other users will see when they interact with you. This is not your band or organization name.' ]); ?>

    </label>

    <!-- Input -->
    <input class="border border-black/20 rounded h-8 p-2" type="text" name="display_name" x-bind:value="accountSettings.display_name_is_cleaned ? '' : accountSettings.display_name">

</div>
