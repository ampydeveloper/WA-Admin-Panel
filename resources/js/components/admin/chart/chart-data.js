export const planetChartData = {
    type: 'line',
    data: {
        labels: [],
        datasets: [
            { 
                label: 'Customers',
                data: [],
                fill: false,
                backgroundColor: [
                    '#11b276', // Green
                ],
                borderColor: [
                    '#11b276',
                ],
                borderWidth: 3
            },
            { 
                label: 'Haulers',
                data: [],
                fill: false,
                backgroundColor: [
                    '#11b276', // Green
                ],
                borderColor: [
                    '#11b276',
                ],
                borderWidth: 3
            }
        ]
    },
    options: {
        responsive: true,
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
                
                ticks: {
                    beginAtZero: true,
                    padding: 25,
                    display: false,
                },
                // gridLines: {
                //     display: false
                // }
            }],
            xAxes: [{
                gridLines: {
                    display: false
                }
            }],
        }
    }
}

export default planetChartData;