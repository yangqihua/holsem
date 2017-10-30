define(['jquery', 'bootstrap', 'backend', 'addtabs', 'table', 'echarts', 'echarts-theme'], function ($, undefined, Backend, Datatable, Table, Echarts) {

    var Controller = {
        index: function () {
            // 基于准备好的dom，初始化echarts实例
            var unitsChart = Echarts.init(document.getElementById('units_echart'));
            var unitsData = function () {
                var date = orderItems.date;
                var keys = orderItems.keys;
                var data = orderItems.data;
                var series = [];

                for (var i = 0; i < keys.length; i++) {
                    var item = {};
                    item['name'] = keys[i];
                    item['type'] = 'bar';
                    item['stack'] = 'orderItem';
                    item['barMaxWidth'] = '40';
                    item['data'] = data[i];
                    series.push(item);
                }
                var total = [];
                for (var i = 0; i < data.length; i++) {
                    for (var j = 0; j < data[i].length; j++) {
                        if (!total.hasOwnProperty(j)) {
                            total[j] = 0;
                        }
                        total[j] += parseInt(data[i][j]);
                        if(j===1){
                        // console.log("total["+j+"]: ", total[j], "data["+i+"]["+j+"]", data[i][j]);
                        }
                    }
                }
                var lineItem = {name: '总数', type: 'line', data: total};
                series.push(lineItem);
                // console.log("series:", series);
                // console.log("data:", data);
                return {date: date, keys: keys, series: series};
            }();
            // 指定图表的配置项和数据
            var unitsOption = {
                "title": {
                    text: '销量详情',
                    textStyle: {
                        color: '#27C24C',
                        fontSize: '16'
                    },
                },
                "tooltip": {
                    "trigger": "axis",
                },
                toolbox: {
                    right: 16,
                    feature: {
                        saveAsImage: {}
                    }
                },
                "grid": {
                    "bottom": 100,
                },
                "legend": {
                    "data": unitsData.keys,
                },

                // "calculable": true,
                "xAxis": [{
                    "type": "category",
                    "splitLine": {
                        "show": false
                    },
                    "axisTick": {
                        "show": false
                    },
                    "splitArea": {
                        "show": false
                    },
                    "data": unitsData.date,
                }],
                "yAxis": [{
                    "type": "value",
                    "splitLine": {
                        "show": false
                    },
                    "axisTick": {
                        "show": false
                    },
                    "axisLabel": {
                        "interval": 0,

                    },
                    "splitArea": {
                        "show": false
                    },

                }],
                "dataZoom": [
                    {
                        "xAxisIndex": [
                            0
                        ],
                        bottom: 30,
                        "start": 0,
                        "end": 100,
                        handleIcon: 'path://M306.1,413c0,2.2-1.8,4-4,4h-59.8c-2.2,0-4-1.8-4-4V200.8c0-2.2,1.8-4,4-4h59.8c2.2,0,4,1.8,4,4V413z',
                        handleSize: '110%',
                        handleStyle: {
                            color: "#aaa",
                        },
                        textStyle: {
                            color: "#27C24C"
                        },
                        borderColor: "#aaa"
                    },
                    {
                        "type": "inside",
                        "show": true,
                        // "height": 15,
                        "start": 30,
                        "end": 100
                    }
                ],
                "series": unitsData.series
            };
            unitsChart.setOption(unitsOption);

            var inventoryChart = Echarts.init(document.getElementById('inventory_echart'));
            var inventoryOption = {
                title: {
                    text: '库存统计',
                    textStyle: {
                        color: '#27C24C',
                        fontSize: '16'
                    },
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data:['s12','x12b','x8b','x8','x5b','x5','a1']
                },
                grid: {
                    "bottom": 100,
                },
                toolbox: {
                    feature: {
                        saveAsImage: {}
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: inventoryChartDataList['time']
                },
                yAxis: {
                    type: 'value'
                },
                dataZoom: [
                    {
                        "xAxisIndex": [
                            0
                        ],
                        bottom: 30,
                        "start": 0,
                        "end": 100,
                        handleIcon: 'path://M306.1,413c0,2.2-1.8,4-4,4h-59.8c-2.2,0-4-1.8-4-4V200.8c0-2.2,1.8-4,4-4h59.8c2.2,0,4,1.8,4,4V413z',
                        handleSize: '110%',
                        handleStyle: {
                            color: "#aaa",
                        },
                        textStyle: {
                            color: "#27C24C"
                        },
                        borderColor: "#aaa"
                    },
                    {
                        "type": "inside",
                        "show": true,
                        // "height": 15,
                        "start": 30,
                        "end": 100
                    }
                ],
                series: [
                    {
                        name:'s12',
                        type:'line',
                        data:inventoryChartDataList['s12']
                    },
                    {
                        name:'x12b',
                        type:'line',
                        data:inventoryChartDataList['x12b']
                    },
                    {
                        name:'x8b',
                        type:'line',
                        data:inventoryChartDataList['x8b']
                    },
                    {
                        name:'x8',
                        type:'line',
                        data:inventoryChartDataList['x8']
                    },
                    {
                        name:'x5b',
                        type:'line',
                        data:inventoryChartDataList['x5b']
                    },
                    {
                        name:'x5',
                        type:'line',
                        data:inventoryChartDataList['x5']
                    },
                    {
                        name:'a1',
                        type:'line',
                        data:inventoryChartDataList['a1']
                    }
                ]
            };
            inventoryChart.setOption(inventoryOption);

        }
    };

    return Controller;
});