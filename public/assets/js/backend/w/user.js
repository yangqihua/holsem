define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'w/user/index',
                    add_url: 'w/user/add',
                    edit_url: 'w/user/edit',
                    del_url: 'w/user/del',
                    multi_url: 'w/user/multi',
                    table: 'w_user',
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
                        {field: 'w_name', title: __('W_name')},
                        {field: 'worker_id', title: __('Worker_id')},
                        {field: 'phone', title: __('Phone')},
                        {field: 'email', title: __('Email')},
                        {field: 'qq', title: __('Qq')},
                        {field: 'title', title: __('Title')},
                        {field: 'sex', title: __('Sex')},
                        {field: 'leave_days', title: __('Leave_days'),visible: false},
                        {field: 'kg_days', title: __('Kg_days'),visible: false},
                        {field: 'cd_days', title: __('Cd_days'),visible: false},
                        {field: 'zt_days', title: __('Zt_days'),visible: false},
                        {field: 'tx_days', title: __('Tx_days'),visible: false},
                        {field: 'cq_days', title: __('Cq_days'),visible: false},
                        // {field: 'update_time', title: __('Update_time'), formatter: Table.api.formatter.datetime},
                        // {field: 'create_time', title: __('Create_time'), formatter: Table.api.formatter.datetime},
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