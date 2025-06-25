    const data = {
        labels: ['Completed', 'Remaining'],
        datasets: [{
            label: 'Task Completion',
            data: [completedTasks, remainingTasks],
            backgroundColor: ['rgb(16, 185, 129)', 'rgb(255, 68, 68)'],
            hoverOffset: 10
        }]
    };

    // ✅ Plugin to show text in center
    const centerTextPlugin = {
        id: 'centerText',
        beforeDraw(chart) {
            const {
                width
            } = chart;
            const {
                height
            } = chart;
            const {
                ctx
            } = chart;

            ctx.restore();
            const fontSize = (height / 150).toFixed(2);
            ctx.font = `${fontSize}em sans-serif`;
            ctx.textBaseline = "middle";

            const text = `${totalTasks}`;
            const textX = Math.round((width - ctx.measureText(text).width) / 2);
            const textY = height / 2;

            ctx.fillStyle = '#111827'; // dark text
            ctx.fillText(text, textX, textY);
            ctx.save();
        }
    };

    const config = {
        type: 'doughnut',
        data: data,
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        },
        plugins: [centerTextPlugin] // 👈 Plugin added here
    };

    new Chart(
        document.getElementById('myDoughnutChart'),
        config
    );

        const ctx = document.getElementById('userTaskChart').getContext('2d');

    const userChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: userLabels,
            datasets: [
                {
                    label: 'Total Tasks',
                    data: totalTasksData,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Completed Tasks',
                    data: completedTasksData,
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Task Count'
                    }
                }
            }
        }
    });