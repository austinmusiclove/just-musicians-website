
<?php
$element_id = 'message-' . $args['conversation_id'] . '-' . $args['message_id'];
?>

<div id="<?php echo $element_id; ?>" class="w-fit max-w-[75%] bg-yellow-light-50 rounded p-3 text-sm <?php if ($args['is_outgoing']) { echo 'ml-auto'; }?>"
    <?php if ($args['last']) { // infinite scroll; include this on the last result of the page ?>
        hx-get="<?php echo site_url('/wp-html/v1/messages/' . $args['conversation_id'] . '/?cursor=' . $args['message_id'] ); ?>"
        hx-trigger="intersect once"
        hx-swap="afterbegin"
        hx-target="#message-board"
        hx-indicator="#mb-spinner"
        hx-on::after-request="if (event.detail.successful) { dispatchEvent(new CustomEvent('scroll-to', {detail: {id: '<?php echo $element_id; ?>'}})); }"
    <?php } ?>
>
    <?php echo nl2br(esc_html($args['content'])); ?>
</div>
