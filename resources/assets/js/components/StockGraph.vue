<template>
    <div></div>
</template>

<script>
    import highstock from 'highcharts/highstock';
    import axios from 'axios';
    export default {
        props: ['id'],

        mounted() {
            axios.get('/api/graph/stock/' + this.id).then(response => {
                highstock.stockChart({
                    chart: {
                        renderTo: this.$el
                    },
                    series: [
                        {
                            data: response.data
                        }
                    ]
                });
            });
        }
    }
</script>

<style>
</style>
