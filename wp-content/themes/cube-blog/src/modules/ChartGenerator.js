
class ChartGenerator {
    constructor() {
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
