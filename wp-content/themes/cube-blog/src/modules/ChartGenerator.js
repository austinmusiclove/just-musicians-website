
class ChartGenerator {
    constructor() {
    }

    generateBarChart(containerId, chartTitle, chartLabels, chartData) {
        const ctx = document.getElementById(containerId);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    borderWidth: 1
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } },
                indexAxis: 'y',
                plugins: {
                    title: {
                        display: true,
                        text: chartTitle
                    },
                    legend: { display: false }
                }
            }
        });
    }

    generatePolarAreaChart(containerId, chartTitle, chartLabels, chartData) {
        const ctx = document.getElementById(containerId);

        new Chart(ctx, {
            type: 'polarArea',
            data: {
                labels: chartLabels,
                datasets: [{ data: chartData, }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top', },
                    title: {
                        display: true,
                        text: chartTitle,
                        font: { size: 22 }
                    }
                }
            }
        });
    }

}

export default ChartGenerator
