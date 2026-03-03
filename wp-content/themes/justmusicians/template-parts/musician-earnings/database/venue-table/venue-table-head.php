<thead>
    <tr class="border-b-2 border-black">
        <th class="py-3 pr-4 font-bold align-top">Venue</th>
        <th class="py-3 pr-4 font-bold cursor-pointer select-none align-top" x-on:click="sortBy('total_reports')">
            <span class="flex items-start gap-1">
                Total Reports
                <span class="flex flex-col gap-1">
                    <span :class="{ 'opacity-100': sortKey === 'total_reports' && sortAsc, 'opacity-20': sortKey !== 'total_reports' || !sortAsc}">↑</span>
                    <span :class="{ 'opacity-100': sortKey === 'total_reports' && !sortAsc, 'opacity-20': sortKey !== 'total_reports' || sortAsc}">↓</span>
                </span>
            </span>
        </th>
        <th class="py-3 pr-4 font-bold cursor-pointer select-none align-top" x-on:click="sortBy('median_earnings')">
            <span class="flex items-start gap-1">
                <span>
                    Median Earnings
                    <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'Median of earnings per gig' ]); ?>
                </span>
                <span class="flex flex-col gap-1">
                    <span :class="{ 'opacity-100': sortKey === 'median_earnings' && sortAsc, 'opacity-20': sortKey !== 'median_earnings' || !sortAsc}">↑</span>
                    <span :class="{ 'opacity-100': sortKey === 'median_earnings' && !sortAsc, 'opacity-20': sortKey !== 'median_earnings' || sortAsc}">↓</span>
                </span>
            </span>
        </th>
        <th class="py-3 pr-4 font-bold cursor-pointer select-none align-top" x-on:click="sortBy('average_earnings')">
            <span class="flex items-start gap-1">
                <span>
                    Average Earnings
                    <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'The average amount earned per gig' ]); ?>
                </span>
                <span class="flex flex-col gap-1">
                    <span :class="{ 'opacity-100': sortKey === 'average_earnings' && sortAsc, 'opacity-20': sortKey !== 'average_earnings' || !sortAsc}">↑</span>
                    <span :class="{ 'opacity-100': sortKey === 'average_earnings' && !sortAsc, 'opacity-20': sortKey !== 'average_earnings' || sortAsc}">↓</span>
                </span>
            </span>
        </th>
        <th class="py-3 pr-4 font-bold align-top">Average Ensemble Size</th>
        <th class="py-3 pr-4 font-bold align-top">Average Set Length (minutes)</th>
        <th class="py-3 pr-4 font-bold align-top">
            <span>
                Payment Method
                <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'Most commonly reported payment method' ]); ?>
            </span>
        </th>
        <th class="py-3 font-bold align-top">
            <span>
                Payment Speed
                <?php echo get_template_part('template-parts/global/tooltip', '', [ 'tooltip' => 'Most commonly reported payment speed' ]); ?>
            </span>
        </th>
    </tr>
</thead>
