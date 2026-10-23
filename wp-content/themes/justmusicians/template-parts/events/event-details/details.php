<div>
    <h3 class="font-bold text-16 mb-2">Details</h3>
    <div class="flex gap-2 flex-col text-14 wysiwyg-content border border-black/20 p-4 rounded whitespace-pre-wrap"
        :class="details ? '' : 'text-black/50'" x-html="details ? details : 'No details provided'">
    </div>
</div>
