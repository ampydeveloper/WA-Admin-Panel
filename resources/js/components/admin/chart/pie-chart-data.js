export const planetChartData = {
    type: 'doughnut',
    data: {
        labels: ['Customers', 'Haulers', 'Outstanding'],
        datasets: [
            { 
                label: 'Invoices',
                data: [0, 0, 0],
                backgroundColor: [
                    '#4DBF7B', // Green
                    '#C9F1E2',
                    '#86E1BF'
                ],
                borderColor: [
                    '#47b784',
                ],
                borderWidth: 2
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        lineTension: 1,
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                display: false,
                ticks: {
                    beginAtZero: true,
                    padding: 1,
                    display: false
                },
                gridLines: {
                    display: false,
                    drawBorder: false,
                }
            }],
            xAxes: [{
                display: false,
                gridLines: {
                    display: false,
                    drawBorder: false,
                }
            }],
        }
    }
}

export default planetChartData;