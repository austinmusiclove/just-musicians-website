<div class=""
    x-data="{ show: true }"
    x-show="show"
    x-init="show = false; requestAnimationFrame(() => { show = true; setTimeout(() => show = false, 4000); });"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
>
    <?php echo $args['message']; ?>
</div>
