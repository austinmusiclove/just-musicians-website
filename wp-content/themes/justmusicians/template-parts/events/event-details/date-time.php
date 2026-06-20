<!-- Date line -->
<div class="flex items-center gap-1">
    <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/calendar.svg" />
    <span class="text-16" :class="startDate || endDate ? '' : 'text-black/50'" x-text="formatDateLine(startDate, endDate)"></span>
</div>

<!-- Time line -->
<div class="flex items-center gap-1">
    <img style="height: 1rem" src="<?php echo get_template_directory_uri(); ?>/lib/images/icons/clock.svg" />
    <span class="text-16" :class="startTime || endTime ? '' : 'text-black/50'" x-text="formatTimeLine(startTime, endTime)"></span>
</div>
