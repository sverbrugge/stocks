<template>
    <div></div>
</template>

<script>
    import highstock from 'highcharts/highstock';
    import axios from 'axios';
    export default {
        props: [
            'id',
            'name',
            'lines'
        ],

        mounted() {
            axios.get('/api/graph/stock/' + this.id).then(response => {
                let date = new Date();
                let options = {
                    chart: {
                        renderTo: this.$el
                    },
                    rangeSelector: {
                        selected: (date.getDay() > 0 && date.getDay() < 6) ? 0 : 1,

                        buttons: [
            				{
            					type: 'day',
            					count: 1,
            					text: '1d'
            				},
            				{
            					type: 'week',
            					count: 1,
            					text: '1w'
            				},
            				{
            					type: 'week',
            					count: 2,
            					text: '2w'
            				},
                            {
            					type: 'month',
            					count: 1,
            					text: '1m'
            				},
                            {
            					type: 'month',
            					count: 3,
            					text: '3m'
            				},
            				{
            					type: 'month',
            					count: 6,
            					text: '6m'
            				},
            				{
            					type: 'ytd',
            					text: 'YTD'
            				},
            				{
            					type: 'year',
            					count: 1,
            					text: '1y'
            				},
            				{
            					type: 'all',
            					text: 'All'
            				}
            			]
                    },
                    yAxis: {
                        plotLines: []
                    },
                    series: [
                        {
                            name: this.name,
                            data: response.data,
                            tooltip: {
                                valueDecimals: 4
                            }
                        }
                    ]
                };

                if (this.lines) {
                    try {
                        let lines = JSON.parse(this.lines);
                        lines.forEach(line => {
                            options.yAxis.plotLines.push({
                                color: '#ff0000',
                                dashStyle: 'shortdash',
                                zIndex: 10,
                                width: 2,
                                value: parseFloat(line),
                                label: {
                                    text: line
                                }
                            });
                        });
                    } catch (e) {
                        // do nothing
                    }
                }
                highstock.stockChart(options);
            });
        }
    }
</script>

<style>
</style>
