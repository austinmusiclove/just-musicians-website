<?php
$proposal_id   = $args['proposal_id'];
$request_quote = $args['request_quote'];
$request_draw  = $args['request_draw'];
$device        = $args['device'];
?>

<button type="button" x-on:click="showForm = true" x-show="!showForm" x-cloak
:class="'<?php echo $device; ?>' == 'desktop' ? '' : 'w-full'"
    class="bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 w-fit whitespace-nowrap">
    Respond to Gig
</button>

<form class="w-full" x-show="showForm" x-cloak
    hx-post="<?php echo site_url('/wp-html/v1/proposals/' . $proposal_id . '/respond-to-inquiry/'); ?>"
    hx-target="#result-<?php echo $proposal_id; ?>"
    hx-swap="outerHTML"
>

    <div class="flex flex-col gap-3">
        <textarea name="details" placeholder="Your response..." rows="3" class="border border-black/20 rounded-sm p-2 text-14 w-full" x-model="prop_details"></textarea>

        <div>
            <span class="text-12 text-black/50 font-semibold">Availability</span>
            <div class="flex gap-2 mt-1">
                <label class="cursor-pointer px-3 py-1 rounded-full border border-black/20 text-14 hover:bg-navy-light" :class="availability == 'yes' ? 'bg-navy text-white' : ''">
                    <input type="radio" name="availability" value="yes" class="sr-only" x-model="availability">Yes
                </label>
                <label class="cursor-pointer px-3 py-1 rounded-full border border-black/20 text-14 hover:bg-navy-light" :class="availability == 'no' ? 'bg-navy text-white' : ''">
                    <input type="radio" name="availability" value="no" class="sr-only" x-model="availability">No
                </label>
                <label class="cursor-pointer px-3 py-1 rounded-full border border-black/20 text-14 hover:bg-navy-light" :class="availability == 'partial' ? 'bg-navy text-white' : ''">
                    <input type="radio" name="availability" value="partial" class="sr-only" x-model="availability">Partial
                </label>
            </div>
        </div>

        <?php if ($request_quote) { ?>
        <div>
            <span class="text-12 text-black/50 font-semibold">Quote ($)</span>
            <input type="number" name="quote" min="0" step="0.01" class="border border-black/20 rounded-sm p-2 text-14 w-full mt-1" x-model="quote">
        </div>
        <?php } ?>

        <?php if ($request_draw) { ?>
        <div>
            <span class="text-12 text-black/50 font-semibold">Draw</span>
            <input type="number" name="draw" min="0" class="border border-black/20 rounded-sm p-2 text-14 w-full mt-1" x-model="draw">
        </div>
        <?php } ?>

        <div class="flex gap-2">
            <button type="submit" class="bg-yellow hover:bg-navy text-black hover:text-white px-3 py-2 rounded-sm font-sun-motter text-14 w-fit">
                Submit Response
            </button>
            <button type="button" x-on:click="showForm = false" class="bg-white hover:bg-black/10 text-black px-3 py-2 rounded-sm font-sun-motter text-14 w-fit border border-black/20">
                Cancel
            </button>
        </div>
    </div>
    <div id="result-<?php echo $proposal_id; ?>"></div>
</form>
