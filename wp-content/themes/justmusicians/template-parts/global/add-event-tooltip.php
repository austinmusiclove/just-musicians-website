<div class="absolute -left-2 bottom-full hidden group-hover:block mb-1 rounded-md">
    <div class="bg-black rounded-sm p-4 flex flex-col gap-4 tooltip tooltip-event" style="width: 400px">
        <div class="text-white flex flex-col gap-4">
            <h4 class="font-bold text-20 py-0.5">Gig Details</h4>

            <form>

                <div class="grid grid-cols-2 gap-y-2 gap-x-4">
                    <?php 
                        $fields = ['Event Name', 'Venue', 'Time', 'Link'];
                        foreach ($fields as $field) { 
                    ?>
                        <div class="border-b border-white/40 text-white">
                            <div class="mb-1"><?php echo $field; ?>:</div>
                            <input class="text-grey no-formatting bg-black w-full inline-block" />
                        </div>
                    <?php } ?>
                </div>
                <div>
                    <label class="mb-1 inline-block">Other notes:</label>
                    <textarea class="border border-white/40 p-1 h-20 w-full text-grey no-formatting rounded-sm bg-black"></textarea>
                </div>
            </form>

        </div>
        <div class="flex items-center justify-between font-bold text-16">
            <button class="bg-yellow px-2 py-1 rounded-sm cursor-pointer">Save</button>
            <button class="text-grey hover:text-red cursor-pointer">Delete</button>
        </div>
    </div>
</div>