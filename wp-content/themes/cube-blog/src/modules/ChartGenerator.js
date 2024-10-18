
class ChartGenerator {
    constructor() {
    }

    generatePolarAreaChart(containerId, chartTitle, data) {
        const ctx = document.getElementById(containerId);
        let chartLabels = ['Cash', 'Check', 'Direct Deposit', 'Zelle', 'PayPal', 'Venmo', 'Cash App'];
        let chartData = [
            data.hasOwnProperty(chartLabels[0]) ? data[chartLabels[0]] : 0,
            data.hasOwnProperty(chartLabels[1]) ? data[chartLabels[1]] : 0,
            data.hasOwnProperty(chartLabels[2]) ? data[chartLabels[2]] : 0,
            data.hasOwnProperty(chartLabels[3]) ? data[chartLabels[3]] : 0,
            data.hasOwnProperty(chartLabels[4]) ? data[chartLabels[4]] : 0,
            data.hasOwnProperty(chartLabels[5]) ? data[chartLabels[5]] : 0,
            data.hasOwnProperty(chartLabels[6]) ? data[chartLabels[6]] : 0,
        ];

        new Chart(ctx, {
            type: 'polarArea',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Dataset 1',
                    data: chartData,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                }],
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        pointLabels: {
                            display: true,
                            centerPointLabels: true,
                            font: { size: 18 }
                        }
                    }
                },
                plugins: {
                    legend: { display: false, },
                    title: {
                        display: true,
                        text: chartTitle
                    }
                }
            }
        });
    }
}

export default ChartGenerator
