define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'sales/index',
                    add_url: 'sales/add',
                    edit_url: 'sales/edit',
                    del_url: 'sales/del',
                    multi_url: 'sales/multi',
                    table: 'sales',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                searchFormVisible: true,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), visible: false, operate: false},
                        {field: 'amazon_order_id', title: __('Amazon_order_id'),sortable: true},
                        {field: 'sku', title: __('Sku'),sortable: true},
                        {field: 'item_price', title: __('Item_price'),sortable: true},
                        {field: 'item_promotion', title: __('Item_promotion'),sortable: true},
                        {
                            field: 'purchase_date',
                            title: __('Purchase_date'),
                            formatter: Table.api.formatter.datetime,
                            sortable: true,
                            operate: 'BETWEEN',
                            type: 'datetime',
                            addclass: 'datetimepicker',
                            data: 'data-date-format="YYYY-MM-DD HH:mm:ss"'
                        },
                        // {
                        //     field: 'operate',
                        //     title: __('Operate'),
                        //     events: Table.api.events.operate,
                        //     formatter: Table.api.formatter.operate
                        // }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
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
            }
        }
    };
    return Controller;
});