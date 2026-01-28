
function initBarChart(chartId, indexAxis, labels, data, colors) {

    const ctx = document.getElementById(chartId).getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
            }]
        },
        options: {
            indexAxis: indexAxis,
            responsive: true,
            plugins: {
                legend: { display: false }
            },
        },
    });

}
