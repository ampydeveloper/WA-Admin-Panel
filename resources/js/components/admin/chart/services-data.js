export const planetChartData = {
    type: 'line',
    data: {
        labels: ['0', '1', '2', '3', '4', '5', '6', '7'],
        datasets: [
            { 
                label: '',
                data: [21, 40, 60, 25, 85, 45, 130, 25],
                // fill: false,
                backgroundColor: [
                    'rgb(17 178 118 / 0.1)', // Green
                ],
                borderColor: [
                    '#11b276',
                ],
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        // lineTension: 0,
        legend: {
            display: false
        },
        elements: {
            point:{
                radius: 0
            }
        },
        scales: {
            yAxes: [{
                display: false,
                ticks: {
                    beginAtZero: true,
                    padding: 25,
                },
                gridLines: {
                    display: false
                }
            }],
            xAxes: [{
                display: false,
                gridLines: {
                    display: false
                }
            }],
        }
    }
}

export default planetChartData;