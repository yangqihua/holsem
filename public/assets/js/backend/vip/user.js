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
                        {field: 'createtime', title: 'Register date', formatter: Table.api.formatter.datetime},
                        {field: 'firstname', title: 'Name',formatter:Controller.api.formatter.name},
                        {field: 'email', title: 'E-mail'},
                        {field: 'telephone', title: 'TEL'},
                        {field: 'skus', title: 'SKU/Product'},
                        {field: 'asin', title: 'ASIN'},
                        {field: 'order_id', title:'Order ID'},
                        {field: 'order_date', title:'Order Date'},
                        {field: 'extra', title:'Extra',visible: false},
                        {field: 'remark', title: '备注',visible: false},

                        {field: 'fax', title: __('Fax'),visible: false},
                        {field: 'address_1', title: 'Street',visible: false,},
                        {field: 'address_2', title: 'State',visible: false,},
                        {field: 'city', title: __('City'),visible: false},
                        {field: 'postcode', title: __('Postcode'),visible: false,},
                        {field: 'company', title: __('Company'),visible: false},
                        {field: 'mail_msg', title: '邮件发送内容',visible: false},
                        {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime,visible: false,},
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
            },
            formatter: {//渲染的方法
                name: function (value, row, index) {
                    return row['firstname'] + ' ' + row['lastname'];
                },
            }
        }
    };
    return Controller;
});