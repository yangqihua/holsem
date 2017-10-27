define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'inventory/index',
                    add_url: 'inventory/add',
                    edit_url: 'inventory/edit',
                    del_url: 'inventory/del',
                    multi_url: 'inventory/multi',
                    table: 'inventory',
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
                        // {checkbox: true},
                        // {field: 'id', title: __('Id'),operate: false},
                        {field: 's12', title: __('Holsem-s12')},
                        {field: 'x12b', title: __('Holsem-x12b')},
                        {field: 'x8', title: __('Holsem-x8')},
                        {field: 'x8b', title: __('Holsem-x8b')},
                        {field: 'x5', title: __('Holsem-x5')},
                        {field: 'x5b', title: __('Holsem-x5b')},
                        {field: 'a1', title: __('Holsem-a1')},
                        {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime},
                        // {field: 'updatetime', title: __('Updatetime'), formatter: Table.api.formatter.datetime,operate: false},
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