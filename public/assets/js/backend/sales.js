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
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'amazon_order_id', title: __('Amazon_order_id')},
                        {field: 'sku', title: __('Sku')},
                        {field: 'item_price', title: __('Item_price')},
                        {field: 'item_promotion', title: __('Item_promotion')},
                        {field: 'purchase_date', title: __('Purchase_date')},
                        {field: 'operate', title: __('Operate'), events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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