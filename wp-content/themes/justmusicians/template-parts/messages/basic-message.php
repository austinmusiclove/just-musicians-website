
<?php
$element_id = 'message-' . $args['conversation_id'] . '-' . $args['message_id'];
?>

<div id="<?php echo $element_id; ?>"
    <?php if ($args['last']) { // infinite scroll; include this on the last result of the page ?>
        hx-get="<?php echo site_url('/wp-html/v1/messages/' . $args['conversation_id'] . '/?cursor=' . $args['message_id'] ); ?>"
        hx-trigger="intersect once"
        hx-swap="afterbegin"
        hx-target="#message-board"
        hx-indicator="#mb-spinner"
        hx-on::after-request="if (event.detail.successful) { dispatchEvent(new CustomEvent('scroll-to', {detail: {id: '<?php echo $element_id; ?>'}})); }"
    <?php } ?>
>

    <!-- Timestamp -->
    <div class="text-center text-grey text-14" x-text="new Date('<?php echo $args['timestamp']; ?> UTC').toLocaleString()">
        <?php echo $args['timestamp']; ?>
    </div>


    <!-- Message -->
    <div class="w-fit max-w-[75%] <?php if ($args['is_outgoing']) { echo 'ml-auto'; }?>">


        <!-- Sender name -->
        <div class="text-14 <?php if ($args['is_outgoing']) { echo 'text-right'; } else { echo 'text-left'; } ?>">
            <?php echo $args['sender_name']; ?>
        </div>


        <!-- Message content -->
        <div class="bg-yellow-light-50 rounded p-3 text-sm">
            <?php echo nl2br(esc_html($args['content'])); ?>
        </div>


    </div>

</div>
