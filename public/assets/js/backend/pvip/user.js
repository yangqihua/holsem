define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'pvip/user/index',
                    add_url: 'pvip/user/add',
                    edit_url: 'pvip/user/edit',
                    del_url: 'pvip/user/del',
                    multi_url: 'pvip/user/multi',
                    table: 'pvip_user',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'order_id',
                sortName: 'order_id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'register_date', title: __('Register_date')},
                        {field: 'name', title: __('Name')},
                        {field: 'email', title: __('Email')},
                        {field: 'tel', title: __('Tel')},
                        {field: 'product', title: __('Product')},
                        {field: 'asin', title: __('Asin')},
                        {field: 'order_id', title: __('Order_id')},
                        {field: 'order_date', title: __('Order_date')},
                        {field: 'Warranty', title: __('Warranty')},
                        {field: 'remark1', title: __('Remark1')},
                        {field: 'remark2', title: __('Remark2')},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
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