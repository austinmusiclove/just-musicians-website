<?php

if ( ! isset( $args['title'] ) ) {
	return;
}

$classes = 'px-2 py-2 border border-black/40 rounded-t text-14 font-medium flex cursor-pointer items-center leading-tight bg-yellow-10 text-grey hover:text-black/40';
$classes .= $args['active']
	? ' active'
	: '';

?>

<div data-tab-heading="<?php echo esc_attr(sanitize_title( $args['title'] )); ?>" class="<?php echo esc_attr( $classes ); ?>">
	<span><?php 
		echo esc_html( $args['title'] ); 
		if ($args['required'] == true) {
			echo '<span class="text-red">*</span>';
		}
	?></span>
	<?php if ( isset( $args['number']) ) : ?>
		<span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
			<?php echo esc_html( $args['number'] ); ?>
		</span>
	<?php endif; ?>
</div>
