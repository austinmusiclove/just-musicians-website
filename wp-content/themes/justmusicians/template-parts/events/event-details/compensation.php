<div class="flex flex-col gap-1" x-show="budget || compensation" x-cloak>

    <h3 class="font-bold text-16 mb-2">Compensation Details</h3>

    <span class="text-16" x-show="budget" x-cloak x-text="`Live Music Budget: $${Number(budget).toLocaleString()}`"></span>

    <span class="text-16" x-show="compensation" x-cloak x-text="compensation"></span>

</div>
