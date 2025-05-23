 <!------------ Submit Buttons 
<div class="flex items-center gap-2 justify-end">
    <button class="w-fit relative rounded text-14 border border-black/20 font-bold py-2 px-3 hover:border-black text-grey hover:text-black disabled:bg-grey disabled:text-white" x-ref="submitButton">
        <span class="htmx-indicator-replace">Save draft</span>
        <span class="absolute inset-0 flex items-center justify-center htmx-indicator">
        <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
        </span>
    </button>
    <button id="htmx-submit-button" type="submit" class="w-fit relative rounded text-14 font-bold py-2 px-3 bg-yellow hover:bg-navy hover:text-white disabled:bg-grey disabled:text-white" x-ref="submitButton">
        <span class="htmx-indicator-replace">Publish listing</span>
        <span class="absolute inset-0 flex items-center justify-center htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
        </span>
    </button>
</div> 
----------------->

<!-- Submit buttons for published listing -->
<div class="flex items-center gap-2 justify-end">
    <button class="w-fit relative rounded text-14 border border-black/20 group flex items-center gap-2 font-bold py-2 px-3 hover:border-black text-grey hover:text-black disabled:bg-grey disabled:text-white" x-ref="submitButton">
        <span class="htmx-indicator-replace">View Listing</span>
        <img class="h-4 opacity-50 group-hover:opacity-100" src="<?php echo get_template_directory_uri() . '/lib/images/icons/eye-password-show.svg';?>" />
        <span class="absolute inset-0 flex items-center justify-center htmx-indicator">
        </span>
    </button>
    <button id="htmx-submit-button" type="submit" class="w-fit relative rounded text-14 font-bold py-2 px-3 bg-yellow hover:bg-navy hover:text-white disabled:bg-grey disabled:text-white" x-ref="submitButton">
        <span class="htmx-indicator-replace">Update Listing</span>
        <span class="absolute inset-0 flex items-center justify-center htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
        </span>
    </button> 
</div>