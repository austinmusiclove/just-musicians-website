<?php
$limit      = (int) ($args['limit'] ?? 200);
$text_var   = $args['text_var'];
$text_color = $args['text_color_class'] ?? 'text-black/50';
$text_size  = $args['text_size_class']  ?? 'text-14';
?>

<div
    x-data="{
        expanded: false,
        tooLong: <?php echo "$text_var.length > $limit"; ?> ? true : false,
    }"
>

    <p class="whitespace-pre-wrap <?php echo "$text_color $text_size"; ?>"
        x-text="<?php echo "expanded ? $text_var : $text_var.slice(0, $limit) + (tooLong ? '...' : '')"; ?>"
    ></p>

    <button type="button" class="text-12 text-black/50 underline cursor-pointer w-fit mt-1"
        x-show="tooLong"
        x-on:click="expanded = !expanded"
        x-text="expanded ? 'Show less' : 'Show more'"
    ></button>

</div>
