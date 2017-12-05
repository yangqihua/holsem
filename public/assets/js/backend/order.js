define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'order/index',
                    // add_url: 'order/add',
                    // edit_url: 'order/edit',
                    // del_url: 'order/del',
                    // multi_url: 'order/multi',
                    table: 'order',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                //可以控制是否默认显示搜索单表,false则隐藏,默认为false
                searchFormVisible: true,
                sortName: 'id',
                sortOrder: 'desc',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'),visible: false},
                        {field: 'amazon_order_id', title: __('Amazon_order_id'), style: 'width:228px'},
                        {field: 'package_number', title: __('Package_number')},
                        {field: 'skus', title: __('Skus'),formatter: Controller.api.formatter.skus, operate: false},
                        {field: 'ship_by', title: __('Ship_by'), formatter: Controller.api.formatter.shipBy},
                        // {field: 'has_items', title: __('Has_items'),visible: false},
                        {
                            field: 'order_status',
                            title: __('Order_status'),
                            formatter: Controller.api.formatter.orderStatus,
                            operate: 'LIKE %...%',
                            placeholder: '模糊搜索，*表示任意字符',
                        },
                        {
                            field: 'deliver_status',
                            title: __('Deliver_status'),
                            formatter: Controller.api.formatter.packageStatus,
                            operate: 'LIKE %...%',
                            placeholder: '模糊搜索，*表示任意字符',
                        },
                        {
                            field: 'has_send_mail',
                            title: __('Has_send_mail'),
                            formatter: Controller.api.formatter.mailStatus,
                            searchList: ['0', '1'],
                            events: Controller.api.events.mail,
                            style:'width:159px'
                        },

                        {field: 'buyer_name', title: __('Buyer_name'), operate: false},
                        {
                            field: 'buyer_email',
                            title: __('Buyer_email'),
                            formatter: Controller.api.formatter.email,
                            operate: false
                        },
                        {
                            field: 'update_time',
                            title: __('Update_time'),
                            formatter: Table.api.formatter.datetime,
                            sortable: true,
                            operate: 'BETWEEN', type: 'datetime', addclass: 'datetimepicker', data: 'data-date-format="YYYY-MM-DD HH:mm:ss"'
                        },
                        {field: 'sales_channel', title: __('Sales_channel'), visible: false, operate: false},
                        {field: 'last_update_date', title: __('Last_update_date'), visible: false, operate: false},
                        {field: 'order_type', title: __('Order_type'), visible: false, operate: false},
                        {field: 'purchase_date', title: __('Purchase_date'), visible: false, operate: false},
                        {field: 'is_business_order', title: __('Is_business_order'), visible: false, operate: false},
                        {field: 'ship_service_level', title: __('Ship_service_level'), visible: false, operate: false},
                        {
                            field: 'number_of_items_shipped',
                            title: __('Number_of_items_shipped'),
                            visible: false,
                            operate: false
                        },
                        {
                            field: 'fulfillment_channel',
                            title: __('Fulfillment_channel'),
                            visible: false,
                            operate: false
                        },
                        {
                            field: 'create_time',
                            title: __('Create_time'),
                            formatter: Table.api.formatter.datetime,
                            visible: false,
                            operate: false
                        },
                        // {field: 'latest_ship_date', title: __('Latest_ship_date'),visible: false},
                        {
                            field: 'number_of_items_unshipped',
                            title: __('Number_of_items_unshipped'),
                            visible: false,
                            operate: false
                        },
                        // {field: 'is_replacement_order', title: __('Is_replacement_order'),visible: false},
                        // {field: 'operate', title: __('Operate'), events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            // 快递按钮事件
            $(document).on("click", ".btn-track", function (e) {
                e.preventDefault();
                var ids = Table.api.selectedids(table);
                //循环弹出多个编辑框
                $.each(ids, function (i, id) {
                    Fast.api.open('order/tracker' + "?id=" + id, "快递信息列表");
                });
            });

            // 商品按钮事件
            $(document).on("click", ".btn-order-item", function (e) {
                e.preventDefault();
                var ids = Table.api.selectedids(table);
                //循环弹出多个编辑框
                $.each(ids, function (i, id) {
                    Fast.api.open('order/items' + "?id=" + id, "商品列表");
                });
            });
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {//渲染的方法
                orderStatus: function (value, row, index) {
                    var colorArr = {
                        Shipped: 'success', Pending: 'warning', Canceled: 'danger', Unshipped: 'info',  // order status
                    };
                    value = value === null ? "无状态" : value;
                    var color = value && typeof colorArr[value] !== 'undefined' ? colorArr[value] : 'primary';
                    return '<span class="label label-' + color + '">' + __(value) + '</span>';
                },
                skus: function (value, row, index) {
                    // var skus = value.split(',');
                    // var skuArr = [];
                    // $.each(skus, function (i, value) {
                    //     arr = value.split('-');
                    //     if(arr.length===2){
                    //         skuArr.push(arr[1]);
                    //     }
                    // });
                    // value = skuArr.join(',');
                    value = value.replace(/HOLSEM[-| ]/g,'');
                    return value;
                },
                shipBy: function (value, row, index) {
                    value = value===null?'':value.toString();
                    return '<a href="javascript:;" class="searchit" data-field="' + this.field + '" data-value="' + value + '">' + value + '</a>';
                },
                packageStatus: function (value, row, index) {
                    var colorArr = {
                        delivered: 'success', in_transit: 'warning', unknown: 'danger',  // package status
                    };
                    var html = [];
                    value = (value===null || value==='') ? "未爬取" : value;
                    var arr = value.split("_");
                    $.each(arr, function (i, value) {
                        var color = value && typeof colorArr[value] !== 'undefined' ? colorArr[value] : 'primary';
                        html.push('<span class="label label-' + color + '">' + __(value) + '</span>');
                    });
                    return html.join(' ');
                },
                mailStatus: function (value, row, index) {
                    var colorArr = {
                        '1': 'success', '0': 'danger',  // package status
                    };
                    value = value === null ? "0" : value.toString();
                    var val = value === '0' ? '未发送' : '已发送';
                    var color = value && typeof colorArr[value] !== 'undefined' ? colorArr[value] : 'primary';
                    // return '<span class="label label-' + color + '">' + __(val) + '</span>';
                    return '<a class="btn btn-xs btn-mail btn-' + color + ' btn-change">' + __(val) + '</a>';
                },
                email: function (value, row, index) {
                    value = value === null ? '无邮箱' : value;
                    return '<div class="input-group input-group-sm" style="width:150px;"><input type="text" class="form-control input-sm" value="' + value + '"><span class="input-group-btn input-group-sm"></span></div>';
                },

            },
            events: {//绑定事件的方法
                mail: {
                    //格式为：方法名+空格+DOM元素
                    'click .btn-mail': function (e, value, row, index) {
                        e.stopPropagation();
                        var container = $("#table").data("bootstrap.table").$container;
                        $("form.form-commonsearch [name='has_send_mail']", container).val(value);
                        $("form.form-commonsearch", container).trigger('submit');
                        // Toastr.info("执行了自定义搜索操作");
                    }
                },
            }
        },

    };
    return Controller;
});