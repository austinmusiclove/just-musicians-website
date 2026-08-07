<?php
$text       = $args['text'] ?? '';
$text_color = $args['text_color_class'] ?? 'text-black/50';
$text_size  = $args['text_size_class']  ?? 'text-14';
?>

<div x-data="{ expanded: false, isOverflowing: false }">

    <!-- Render 100% of the full text in HTML source for search engines -->
    <!-- x-init once when Alpine initializes this element. Detects whether the text is actually clipped
         by line-clamp-6 so the "Show more" button only appears when the content is truly truncated. -->
    <p class="whitespace-pre-wrap <?php echo "$text_color $text_size"; ?> line-clamp-6"
       x-init="$nextTick(() => {
           // scrollHeight = full text height (incl. the part hidden by the clamp),
           // clientHeight = visible box height. If scroll > client, content is cut off.
           // The !expanded guard stops re-measuring once the user expands, so the button doesn't vanish.
           const measure = () => { if (!expanded) { isOverflowing = $el.scrollHeight > $el.clientHeight; } };

           // $nextTick waits for Alpine to apply the :class binding (line-clamp-6) before measuring,
           // so we measure the clamped layout rather than the pre-Alpine full-height text.
           measure();

           // Text wrapping depends on the loaded font, so a bio can overflow only after webfonts load.
           // document.fonts.ready is a Promise (pending while fonts load, resolved when done); .then() runs
           // measure as soon as it settles. The ?. only guards browsers lacking the Font Loading API.
           if (document.fonts?.ready) { document.fonts.ready.then(measure); }
       })"
       :class="expanded ? '!line-clamp-none' : 'line-clamp-6'"><?php echo htmlspecialchars($text); ?></p>

    <button type="button"
            class="text-12 text-black/50 underline cursor-pointer w-fit mt-1"
            x-show="isOverflowing"
            x-cloak
            x-on:click="expanded = !expanded"
            x-text="expanded ? 'Show less' : 'Show more'">
        Show more
    </button>

</div>
