
<div
    x-bind:hx-get="'<?php echo site_url('/wp-html/v1/events/'); ?>' + eventId + '/applicants'"
    hx-trigger="load"
    hx-target="#applicant-results"
    hx-swap="outerHTML"
>
    <div class="htmx-indicator py-8 text-center">Loading applicants...</div>
</div>
<div id="applicant-results"></div>
