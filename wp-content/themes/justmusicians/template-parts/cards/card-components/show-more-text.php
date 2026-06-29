<?php
$limit = (int) $args['limit'] ?? 200;
?>

<div
    x-data="{
        expanded: false,
        tooLong: <?php echo $args['text_var'] . '.length > ' . $limit; ?> ? true : false,
    }"
>

    <p class="text-14 whitespace-pre-wrap <?php echo $args['text_color_class'] ?? 'text-black/50'; ?>"
        x-text="expanded ? <?php echo $args['text_var']; ?> : <?php echo $args['text_var']; ?>.slice(0, <?php echo $limit; ?>) + (tooLong ? '...' : '')"
    ></p>

    <button type="button" class="text-12 text-black/50 underline cursor-pointer w-fit mt-1"
        x-show="tooLong"
        x-on:click="expanded = !expanded"
        x-text="expanded ? 'Show less' : 'Show more'"
    ></button>

</div>
