export const planetChartData = {
    type: 'pie',
    data: {
        // labels: ['0', '1', '2', '3', '4', '5', '6', '7'],
        datasets: [
            // { // one line graph
            //     label: 'Number of Moons',
            //     data: [0, 0, 1, 2, 67, 62, 27, 14],
            //     backgroundColor: [
            //         'rgba(54,73,93,.5)', // Blue
            //         'rgba(54,73,93,.5)',
            //         'rgba(54,73,93,.5)',
            //         'rgba(54,73,93,.5)',
            //         'rgba(54,73,93,.5)',
            //         'rgba(54,73,93,.5)',
            //         'rgba(54,73,93,.5)',
            //         'rgba(54,73,93,.5)'
            //     ],
            //     borderColor: [
            //         '#36495d',
            //         '#36495d',
            //         '#36495d',
            //         '#36495d',
            //         '#36495d',
            //         '#36495d',
            //         '#36495d',
            //         '#36495d',
            //     ],
            //     borderWidth: 3
            // },
            { // another line graph
                label: 'Invoices',
                data: [116.4, 50.7, 49.2],
                backgroundColor: [
                    '#4DBF7B', // Green
                    '#C9F1E2',
                    '#86E1BF'
                ],
                borderColor: [
                    '#47b784',
                ],
                borderWidth: 3
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
                ticks: {
                    beginAtZero: true,
                    padding: 1,
                },
                gridLines: {
                    display: false,
                    drawBorder: false,
                }
            }],
            xAxes: [{
                gridLines: {
                    display: false,
                    drawBorder: false,
                }
            }],
        }
    }
}

export default planetChartData;