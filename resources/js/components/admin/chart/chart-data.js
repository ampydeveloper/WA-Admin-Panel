export const planetChartData = {
    type: 'line',
    data: {
        labels: ['01', '15', '09', '13', '17', '21', '26', '34'],
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
            { 
                label: '',
                data: [4.8, 60, 120, 50, 130, 80, 140, 70],
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
                label: '',
                data: [21, 40, 60, 25, 85, 45, 130, 25],
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