<template x-if="message.is_outgoing">
    <a href="<?php echo site_url('/account'); ?>">
        <img class="w-8 h-8 rounded-full mt-1" alt="Profile image" x-bind:src="message.sender_profile_image_url">
    </a>
</template>

<template x-if="!message.is_outgoing">
    <img class="w-8 h-8 rounded-full mt-1" alt="Profile image" x-bind:src="message.sender_profile_image_url">
</template>

<!--
<a href="<?php echo site_url('/account'); ?>" x-show="message.is_outgoing" x-cloak>
    <img class="w-8 h-8 rounded-full mt-1" alt="Profile image" x-bind:src="message.sender_profile_image_url">
</a>
<img class="w-8 h-8 rounded-full mt-1" alt="Profile image" x-bind:src="message.sender_profile_image_url" x-show="!message.is_outgoing" x-cloak>
-->
