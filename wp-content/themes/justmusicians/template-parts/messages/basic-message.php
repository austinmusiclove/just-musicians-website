
<?php
$element_id = 'message-' . $args['conversation_id'] . '-' . $args['message_id'];
?>

<style>
.opacity-0 {
    opacity: 0;
}
.opacity-100 {
    opacity: 1;
}
.translate-y-6 {
    transform: translateY(1.5rem); /* 6 * 0.25rem */
}
.translate-y-0 {
    transform: translateY(0);
}
.transition {
    transition: all 0.3s ease-out;
}
.ease-out {
    transition-timing-function: ease-out;
}
.duration-300 {
    transition-duration: 300ms;
}
</style>

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
    <?php if (isset($args['timestamp'])) { ?>
    <div class="text-center text-grey text-14" x-text="new Date('<?php echo $args['timestamp']; ?> UTC').toLocaleString()">
        <?php echo $args['timestamp']; ?>
    </div>
    <?php } ?>


    <!-- Message -->
    <div class="w-fit max-w-[75%] <?php if ($args['is_outgoing']) { echo 'ml-auto'; }?>"
        x-data="{ show: false }"
        x-show="show" x-cloak
        x-init="requestAnimationFrame(() => { show = true })"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-6"
        x-transition:enter-end="opacity-100 translate-y-0"
    >


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
