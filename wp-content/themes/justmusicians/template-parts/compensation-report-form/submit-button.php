<div class="flex items-center gap-2 justify-starb shrink-0">
    <button type="submit" class="htmx-submit-button w-fit relative rounded text-14 font-bold py-2 px-3 bg-navy text-white disabled:opacity-50">


        <span class="htmx-indicator-replace">Submit</span>

        <span class="absolute inset-0 flex items-center justify-center opacity-0 htmx-indicator">
            <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
        </span>


    </button>
</div>
