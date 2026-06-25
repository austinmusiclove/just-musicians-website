<div class="flex flex-col gap-2 mb-4">

    <div>
        <label class="text-14 font-bold">Date</label>
        <input type="date" name="event_start_date" x-bind:value="startDate" class="w-full" required/>
    </div>

    <div class="flex gap-2" x-data="{ startTimeInput: startTime, endTimeInput: endTime }">
        <div class="flex-1">
            <label class="text-14 font-bold">Start Time</label>
            <input type="time" name="event_start_time" x-bind:value="startTime" x-model="startTimeInput" x-bind:max="endTimeInput || '23:59'" class="w-full" />
        </div>
        <div class="flex-1">
            <label class="text-14 font-bold">End Time</label>
            <input type="time" name="event_end_time" x-bind:value="endTime" x-model="endTimeInput" x-bind:min="startTimeInput || '00:00'" class="w-full" />
        </div>
    </div>

</div>
