<template x-if="message.created_at">
    <div class="text-center text-grey text-14 my-2" x-text="new Date(message.created_at.replace(' ', 'T') + 'Z').toLocaleString()"></div>
</template>
