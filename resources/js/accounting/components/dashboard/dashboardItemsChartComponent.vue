<template>
    <div class="panel panel-primary">
        <div class="panel-heading">
            {{title}}
        </div>
        <chart :data="data"
               :options="options"
               :type="type"
               class="has-padding-medium"/>

    </div>
</template>
<script>
    import {EnsoChartCard as ChartCard} from '@enso-ui/charts/bulma';
    import {Chart, colors} from '@enso-ui/charts';

    export default {
        props:["title","type"],
        components: {
            Chart,
            colors,
            ChartCard
        },
        data: function () {
            return {
                // type: 'polarArea',
                // 'line', 'bar', 'radar', 'polarArea', 'pie', 'doughnut', 'bubble'.
                data: {
                    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                    datasets: [{
                        label: '# of Votes',
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }

            }
        }
    }
</script>