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
        function generateSku() {
            $.ajax({
                url: "{{ adminRoute('sku') }}",
                success: (response) => {
                    $("input[name='sku']").val(response);
                }
            })
        }

        function ajaxRequest(params) {
            var url = "{{ adminRoute('products.index') }}"
            $.get(url + '?' + $.param(params.data)).then(function (res) {
                params.success(res.data)
            })
        }

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

        function imgsFormatter(value, row, index) {
            return "<a>点击查看</a>"
        }

        function imgFormatter(value, row, index) {
            return `<img width="100px" src="/${value}">`;
        }

        $('#table').bootstrapTable({
            ajax: function (params) {
                var url = "{{ adminRoute('purchase.index') }}"
                $.get(url + '?' + $.param(params.data)).then(function (res) {
                    console.log(res)
                    const data = res.data;
                    data['total'] = res.total;
                    data['totalNotFiltered'] = res.total;
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
            columns: [
                {
                    checkbox: true
                },
                {
                    field: 'id',
                    title: 'id',
                }, {
                    field: 'remark',
                    title: '留言',
                    class: 'min-width-200'
                }, {
                    field: 'status',
                    title: "状态",

                },{
                    field: "deadline_at",
                    title: '截止时间'
                },{
                    field: 'complete_at',
                    title:'完成时间'
                },{
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
