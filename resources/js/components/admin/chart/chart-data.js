export const planetChartData = {
    type: 'line',
    data: {
        labels: ['Mercury', 'Venus', 'Earth', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune'],
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
                label: '',
                data: [4.8, 12.1, 12.7, 6.7, 139.8, 116.4, 50.7, 49.2],
                backgroundColor: [
                    '#dff7e9', // Green
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
        lineTension: 0,
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    padding: 25,
                }
            }]
        }
    }
}

export default planetChartData;