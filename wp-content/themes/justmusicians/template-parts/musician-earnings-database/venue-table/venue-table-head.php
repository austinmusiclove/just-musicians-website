<thead>
    <tr class="border-b-2 border-black">
        <th class="py-3 pr-4 font-bold">Venue</th>
        <th class="py-3 pr-4 font-bold cursor-pointer select-none" x-on:click="sortBy('total_reports')">
            Total Reports
            <span x-show="sortKey === 'total_reports'" x-text="sortAsc ? '↑' : '↓'" class="ml-1"></span>
        </th>
        <th class="py-3 pr-4 font-bold cursor-pointer select-none" x-on:click="sortBy('median_earnings')">
            Median Earnings
            <span x-show="sortKey === 'median_earnings'" x-text="sortAsc ? '↑' : '↓'" class="ml-1"></span>
        </th>
        <th class="py-3 pr-4 font-bold cursor-pointer select-none" x-on:click="sortBy('average_earnings')">
            Average Earnings
            <span x-show="sortKey === 'average_earnings'" x-text="sortAsc ? '↑' : '↓'" class="ml-1"></span>
        </th>
        <th class="py-3 pr-4 font-bold">Avg Ensemble Size</th>
        <th class="py-3 pr-4 font-bold">Avg Set Length (min)</th>
        <th class="py-3 pr-4 font-bold">Payment Method</th>
        <th class="py-3 font-bold">Payment Speed</th>
    </tr>
</thead>
