define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'vip/user/index',
                    add_url: 'vip/user/add',
                    edit_url: 'vip/user/edit',
                    del_url: 'vip/user/del',
                    multi_url: 'vip/user/multi',
                    table: 'vip_user',
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
                        {field: 'order_id', title: __('Order_id')},
                        {field: 'firstname', title: __('Firstname')},
                        {field: 'email', title: __('Email')},
                        {field: 'telephone', title: __('Telephone')},
                        {field: 'fax', title: __('Fax')},
                        {field: 'lastname', title: __('Lastname')},
                        {field: 'address_1', title: __('Address_1')},
                        {field: 'address_2', title: __('Address_2')},
                        {field: 'city', title: __('City')},
                        {field: 'postcode', title: __('Postcode')},
                        {field: 'company', title: __('Company')},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
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