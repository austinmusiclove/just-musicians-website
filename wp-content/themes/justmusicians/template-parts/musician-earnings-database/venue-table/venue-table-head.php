<thead>
    <tr class="border-b-2 border-black">
        <th class="py-3 pr-4 font-bold">Venue</th>
        <th class="py-3 pr-4 font-bold cursor-pointer select-none" x-on:click="sortBy('total_reports')">
            <span class="flex items-center gap-1">
                Total Reports
                <span class="flex flex-col gap-1">
                    <span class="opacity-50" :class="{ 'opacity-100': sortKey === 'total_reports' && sortAsc, 'opacity-50': sortKey !== 'total_reports' || !sortAsc}">↑</span>
                    <span class="-mt-1 opacity-50" :class="{ 'opacity-100': sortKey === 'total_reports' && !sortAsc, 'opacity-50': sortKey !== 'total_reports' || sortAsc}">↓</span>
                </span>
            </span>
        </th>
        <th class="py-3 pr-4 font-bold cursor-pointer select-none" x-on:click="sortBy('median_earnings')">
            <span class="flex items-center gap-1">
                Median Earnings
                <span class="flex flex-col gap-1">
                    <span class="opacity-50" :class="{ 'opacity-100': sortKey === 'median_earnings' && sortAsc, 'opacity-50': sortKey !== 'median_earnings' || !sortAsc}">↑</span>
                    <span class="-mt-1 opacity-50" :class="{ 'opacity-100': sortKey === 'median_earnings' && !sortAsc, 'opacity-50': sortKey !== 'median_earnings' || sortAsc}">↓</span>
                </span>
            </span>
        </th>
        <th class="py-3 pr-4 font-bold cursor-pointer select-none" x-on:click="sortBy('average_earnings')">
            <span class="flex items-center gap-1">
                Average Earnings
                <span class="flex flex-col gap-1">
                    <span class="opacity-50" :class="{ 'opacity-100': sortKey === 'average_earnings' && sortAsc, 'opacity-50': sortKey !== 'average_earnings' || !sortAsc}">↑</span>
                    <span class="-mt-1 opacity-50" :class="{ 'opacity-100': sortKey === 'average_earnings' && !sortAsc, 'opacity-50': sortKey !== 'average_earnings' || sortAsc}">↓</span>
                </span>
            </span>
        </th>
        <th class="py-3 pr-4 font-bold">Avg Ensemble Size</th>
        <th class="py-3 pr-4 font-bold">Avg Set Length (min)</th>
        <th class="py-3 pr-4 font-bold">Payment Method</th>
        <th class="py-3 font-bold">Payment Speed</th>
    </tr>
</thead>
