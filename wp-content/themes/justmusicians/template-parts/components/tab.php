<?php

if ( ! isset( $args['title'] ) ) {
	return;
}

?>

<div class="pl-2 py-2 border border-black/40 rounded-t text-12 font-medium flex cursor-pointer items-center leading-tight bg-yellow-10 text-grey hover:text-black/40"
data-tab-heading="<?php echo esc_attr(sanitize_title( $args['title'] )); ?>"
    :class="{'active': <?php echo $args['show_exp']; ?>}"
    x-on:click="
        <?php echo $args['hide_exp']; ?>;
        <?php echo $args['show_exp']; ?> = true;
    "
>

	<span><?php
		echo esc_html( $args['title'] );
		if ($args['required'] == true) {
			echo '<span class="text-red">*</span>';
		}
	?></span>


    <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full"
        x-text="<?php echo $args['count_exp']; ?>"
    >
    </span>


</div>
