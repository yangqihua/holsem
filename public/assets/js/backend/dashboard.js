define(['jquery', 'bootstrap', 'backend', 'addtabs', 'table', 'echarts', 'echarts-theme'], function ($, undefined, Backend, Datatable, Table, Echarts) {

    var Controller = {
        index: function () {
            // 基于准备好的dom，初始化echarts实例
            var myChart = Echarts.init(document.getElementById('echart'));

            var xData = function () {
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
                console.log("series:", series);
                // console.log("data:", data);
                return {date: date, keys: keys, series: series};
            }();

            // 指定图表的配置项和数据
            var option = {
                "title": {
                    text: '订单详情',
                    textStyle: {
                        color: '#27C24C',
                        fontSize: '16'
                    },
                },
                "tooltip": {
                    "trigger": "axis",
                    // "axisPointer": {
                    //     "type": "shadow",
                    //     textStyle: {
                    //         color: "#fff"
                    //     }
                    // },
                },
                toolbox: {
                    right: 16,
                    feature: {
                        saveAsImage: {}
                    }
                },
                "grid": {
                    // "borderWidth": 0,
                    // "top": 110,
                    "bottom": 100,
                    // textStyle: {
                    //     color: "#fff"
                    // }
                },
                "legend": {
                    // x: '4%',
                    // top: '11%',
                    // textStyle: {
                    //     color: '#90979c',
                    // },
                    "data": xData.keys,
                },

                // "calculable": true,
                "xAxis": [{
                    "type": "category",
                    // "axisLine": {
                    //     lineStyle: {
                    //         color: '#90979c'
                    //     }
                    // },
                    "splitLine": {
                        "show": false
                    },
                    "axisTick": {
                        "show": false
                    },
                    "splitArea": {
                        "show": false
                    },
                    // "axisLabel": {
                    //     "interval": 0,
                    // },
                    "data": xData.date,
                }],
                "yAxis": [{
                    "type": "value",
                    "splitLine": {
                        "show": false
                    },
                    // "axisLine": {
                    //     lineStyle: {
                    //         color: '#90979c'
                    //     }
                    // },
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
                        // "show": true,
                        // "height": 30,
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
                "series": xData.series,
                //     [
                //     {
                //         "name": "OA",
                //         "type": "bar",
                //         "stack": "总量",
                //         "barMaxWidth": 40,
                //         "data": [
                //             709,
                //             1917,
                //             2455,
                //             2610,
                //             1719,
                //             1433,
                //             1544,
                //             3285,
                //             5208,
                //             3372,
                //             2484,
                //             4078
                //         ],
                //     },
                //
                //     {
                //         "name": "手工录入",
                //         "type": "bar",
                //         "stack": "总量",
                //         "data": [
                //             327,
                //             1776,
                //             507,
                //             1200,
                //             800,
                //             482,
                //             204,
                //             1390,
                //             1001,
                //             951,
                //             381,
                //             220
                //         ]
                //     },
                //
                //     {
                //         "name": "企业号",
                //         "type": "bar",
                //         "stack": "总量",
                //         "data": [
                //             327,
                //             1776,
                //             507,
                //             1200,
                //             800,
                //             482,
                //             204,
                //             1390,
                //             1001,
                //             951,
                //             381,
                //             220
                //         ]
                //     }, {
                //         "name": "总数",
                //         "type": "line",
                //         "data": [
                //             1363,
                //             3693,
                //             2962,
                //             3810,
                //             2519,
                //             1915,
                //             1748,
                //             4675,
                //             6209,
                //             4323,
                //             2865,
                //             4298
                //         ]
                //     },
                // ]
            };

            myChart.setOption(option);
        }
    };

    return Controller;
});