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
                        // {field: 'id', title: __('Id')},
                        {field: 'order_id', title: __('Order_id')},
                        {field: 'skus', title: '购买的sku'},
                        {field: 'firstname', title: __('Firstname')},
                        {field: 'lastname', title: __('Lastname')},
                        {field: 'email', title: __('Email')},
                        {field: 'telephone', title: __('Telephone')},
                        {field: 'fax', title: __('Fax')},
                        {field: 'address_1', title: __('Address_1'),visible: false,},
                        {field: 'address_2', title: __('Address_2'),visible: false,},
                        {field: 'city', title: __('City')},
                        {field: 'postcode', title: __('Postcode'),visible: false,},
                        {field: 'company', title: __('Company')},
                        {field: 'mail_msg', title: '邮件发送内容',visible: false},
                        {field: 'remark', title: '备注',visible: false},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime,visible: false,},
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