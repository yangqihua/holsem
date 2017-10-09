define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'upload'], function ($, undefined, Backend, Table, Form, Upload) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'w/sign/index',
                    add_url: 'w/sign/add',
                    edit_url: 'w/sign/edit',
                    del_url: 'w/sign/del',
                    multi_url: 'w/sign/multi',
                    table: 'w_sign',
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
                        {field: 'worker_id', title: __('Worker_id')},
                        {field: 'sign_date', title: __('Sign_date')},
                        {field: 'start_time', title: __('Start_time')},
                        {field: 'end_time', title: __('End_time')},
                        {field: 'create_time', title: __('Create_time'), formatter: Table.api.formatter.datetime},
                        {field: 'update_time', title: __('Update_time'), formatter: Table.api.formatter.datetime},
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

            // 快递按钮事件
            $(document).on("click", ".btn-upload", function (e) {
                e.preventDefault();
                Fast.api.open('w/sign/add', "上传门禁记录");
            });

            // Upload.api.custom.changetable = function (response) {
            //     console.log('response', response);
            //     // var data = response.data.data;
            //     // var content = '';
            //     // for(var i=0;i<data.length;i++){
            //     //     content+='<tr><td>'+data.name+'</td><td>'+data.date+'</td><td>'+data.start_time+'</td><td>'+data.end_time+'</td></tr>'
            //     // }
            //     // console.log('content',content);
            //     // $('.table > tbody').html(content);
            // };
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