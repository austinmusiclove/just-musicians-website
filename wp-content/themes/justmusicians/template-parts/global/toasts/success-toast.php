<div class="text-16 sm:text-18 bg-yellow-60 p-2 text-center sticky w-full"
    x-data="{ show: false, message: '' }"
    x-show="show" x-cloak
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    x-on:success-toast.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 5000)"
    <?php if (!empty($args['message'])) { ?> x-init="$nextTick(() => { $dispatch('success-toast', { 'message': '<?php echo $args['message']; ?>'}); })" <?php } ?>
>
    <span x-text="message"></span>
    <img class="close-button opacity-60 hover:opacity-100 absolute top-0.5 right-0.5 cursor-pointer" src="<?php echo get_template_directory_uri() . '/lib/images/icons/close-small.svg';?>" x-on:click="show = false"/>
</div>
