<form
    x-bind:hx-post="`<?php echo site_url(); ?>/wp-html/v1/inquiries/${inquiry.inquiry_id}`"
    hx-target="#inquiry-result"
    hx-ext="disable-element" hx-disable-element=".inquiry-submit-button"
    x-data="{
        subjectInput: inquiry.subject,
    }"
>

    <!-- Subject -->
    <h3 class="my-2 font-bold text-16">Subject</h3>
    <p class="text-14">At least 15 characters (<span x-text="subjectInput.length">0</span>/15)</p>
    <input type="text" name="inquiry_subject" class="w-full" placeholder="example: Wedding ceremony music in Dripping Springs, TX" x-bind:value="inquiry.subject" x-model="subjectInput" />

    <!-- Details -->
    <h3 class="my-2 font-bold text-16">Details</h3>
    <textarea class="w-full h-24 sm:h-40 mb-6" name="inquiry_details" x-bind:value="inquiry.raw_details"></textarea>


    <!-- Submit and Cancel -->
    <div class="flex flex-row gap-2">
        <button type="button" class="w-fit relative rounded text-14 border border-black/20 group flex items-center gap-2 font-bold py-2 px-3 hover:border-black text-grey hover:text-black disabled:opacity-50"
            x-on:click="editInquiryMode = false;"
        >Cancel</button>
        <button type="submit" class="htmx-submit-button w-fit relative rounded text-14 font-bold py-2 px-3 bg-navy text-white disabled:opacity-50">
            <span class="htmx-indicator-replace">Update</span>
            <span class="absolute inset-0 flex items-center justify-center opacity-0 htmx-indicator">
                <?php echo get_template_part('template-parts/global/spinner', '', ['size' => '4', 'color' => 'white']); ?>
            </span>
        </button>
    </div>

</form>
<span id="inquiry-result"></span>
