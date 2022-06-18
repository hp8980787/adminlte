@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>采购</h1>
@stop

@section('content')
    <div class="row ml-3 mt-3">
        <a href="{{ adminRoute('purchase.create') }}">
            <x-adminlte-button label="创建" theme="primary" icon="fas fa-plus"/>
        </a>
    </div>
    <div class="container">
        <div class="row">
            <table id="table"></table>
        </div>
    </div>


@stop

@section('css')
    <style>
        .min-width-200 {
            min-width: 200px
        }

        .min-width-100 {
            min-width: 100px;
        }

    </style>

@stop

@section('js')



    <script>


        function operateFormatter(value, row, index) {
            return [
                '<a class="edit" href="javascript:void(0)" title="edit">',
                '<i class="fas fa-edit"></i>',
                '</a>  ',
                '<a class="remove" href="javascript:void(0)" title="Remove">',
                '<i class="fa fa-trash"></i>',
                '</a>'
            ].join('')
        }

        window.operateEvents = {
            'click .edit': function (e, value, row, index) {
                window.location.href = row['editUrl'];
            },
            'click .remove': function (e, value, row, index) {
                const delUrl = row['delUrl'];
                Swal.fire({
                    title: '是否删除?',
                    text: "你将会删除这条数据",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: delUrl,
                            method: 'delete',
                            data: {},
                            success: (response) => {
                                $('#table').bootstrapTable('refresh')
                                Swal.fire(
                                    '删除成功!',
                                    '删除成功!',
                                    'success'
                                )
                            }
                        });

                    }
                })
            }
        }

        function expendFormatter(value, row, index) {
            let items = row.items;
            let table = "<table class='table'><thead><tr><th>product_id</th><th>说明</th><th>采购价格</th><th>采购数量</th><th>采购总价</th></tr></thead>";
            table += "<tbody>"
            for (let i in items) {
                table += `<tr><td>${items[i].product_id}</td><td>${items[i].explain}</td><td>${items[i].price}</td>
<td>${items[i].quantity}</td> <td>${items[i].amount}</td></tr>`;
            }
            table += "</tbody>"
            return table;
        }

        function statusFormatter(value, row, index) {
            switch (value) {
                case '待审核':
                    return `<span><i class="fa fa-circle text-danger"  aria-hidden="true"></i>待审核</span>`;
                case '采购完成':
                    return `<span><i class="fa fa-circle text-success"  aria-hidden="true"></i>采购完成</span>`;
                case '审核通过,正在采购':
                    return `<span><i class="fa fa-circle text-warning"  aria-hidden="true"></i>审核通过,正在采购</span>`;
            }
        }

        $('#table').bootstrapTable({
            ajax: function (params) {
                var url = "{{ adminRoute('purchase.index') }}"
                $.get(url + '?' + $.param(params.data)).then(function (res) {
                    const data = res.data;
                    data['total'] = res.meta.total;
                    data['totalNotFiltered'] =res.meta.total;
                    params.success(data)
                })
            },
            queryParamsType: '',
            queryParams: function (params) {
                return {
                    perPage: params.pageSize,   //页面大小
                    search: params.searchText, //搜索
                    order: params.order, //排序
                    ordername: params.sort, //排序
                    page: params.pageNumber,
                };
            },
            showHeader: true,
            showColumns: true,
            hideColumn: ['sku'],
            showRefresh: true,
            pagination: true,//分页
            sidePagination: 'server',//服务器端分页
            pageNumber: 1,
            pageList: [5, 10, 20, 50, 100],//分页步进值
            search: true,//显示搜索框
            detailView: true,
            detailFormatter: expendFormatter,

            columns: [
                {
                    checkbox: true
                },
                {
                    field: 'id',
                    title: 'id',
                }, {
                    field: "title",
                    title: "title",
                }, {
                    field: 'remark',
                    title: '留言',
                    class: 'min-width-200'
                }, {
                    field: 'status',
                    title: "状态",
                    formatter: statusFormatter

                }, {
                    field: "deadline_at",
                    title: '截止时间'
                }, {
                    field: 'complete_at',
                    title: '完成时间'
                }, {
                    field: 'operate',
                    title: '操作',
                    align: 'center',
                    clickToSelect: false,
                    class: 'min-width-100',
                    events: window.operateEvents,
                    formatter: operateFormatter
                }
            ]
        })

    </script>
@stop

@section('plugins.BsCustomFileInput', true)
@section('plugins.Summernote', true)
@section('plugins.Sweetalert2', true)
@section('plugins.BootstrapTable',true)
