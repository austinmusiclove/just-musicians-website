<?php
$event_name = !empty($args['event_name']) ? $args['event_name'] : 'success-toast';
?>
<div class=""
    x-data="{ show: false, message: '' }"
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    x-on:<?php echo $event_name; ?>.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
    <?php if (!empty($args['message'])) { ?> x-init="$nextTick(() => { $dispatch('<?php echo $event_name; ?>', { 'message': '<?php echo $args['message']; ?>'}); })" <?php } ?>
>
    <span x-text="message"></span>
</div>
