export const planetChartData = {
    type: 'line',
    data: {
        labels: [],
        datasets: [
            { 
                label: '',
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
            // { 
            //     label: '',
            //     data: [21, 40, 60, 25, 85, 45, 130, 25],
            //     fill: false,
            //     backgroundColor: [
            //         '#11b276', // Green
            //     ],
            //     borderColor: [
            //         '#11b276',
            //     ],
            //     borderWidth: 3
            // }
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