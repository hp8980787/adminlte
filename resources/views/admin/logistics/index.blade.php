@extends('adminlte::page')
@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ adminRoute('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">物流</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <x-adminlte-button label="创建物流公司" data-toggle="modal" data-target="#createModal" theme="info"/>
            </div>
            <div class="col-md-12">
                <table id="table"></table>
            </div>


        </div>

        <div class="row">
            <x-adminlte-modal id="createModal" title="创建物流公司" theme="teal">
                <form action="{{ adminRoute('logistics.store') }}" method="POST">
                    @csrf
                    <x-adminlte-input name="name" label="名称" required/>
                    <x-adminlte-input name="url" label="查询网址" required/>
                    <x-adminlte-button label="提交" type="submit" theme="success"/>
                </form>
            </x-adminlte-modal>
        </div>
    </div>
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
                '</a>',
            ].join('')
        }

        window.operateEvents = {
            'click .remove': function (e, value, row, index) {
                Swal.fire({
                    title: '确定删除吗?',
                    text: "删除不可逆!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '确定删除'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `${row.delUrl}`,
                            method: 'delete',
                            async: false,
                            success: function (res) {

                                Swal.fire(
                                    '成功!',
                                    '你已经成功删除',
                                    'success'
                                )
                                $('#table').bootstrapTable('refresh')
                            }, error: function (e) {
                                Swal.fire(
                                    '失败!',
                                    '删除失败',
                                    'error'
                                )
                            }
                        })
                    }
                })
            }
        }
        const $table = $('#table')
        $table.bootstrapTable({
            ajax: function (params) {
                let url = "{{ adminRoute('logistics.index') }}"
                $.get(url + '?' + $.param(params.data)).then(function (res) {
                    const data = res.data;
                    data['total'] = res.meta.total;
                    data['totalNotFiltered'] = res.meta.total;
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
            showRefresh: true,
            pagination: true,//分页
            sidePagination: 'server',//服务器端分页
            pageNumber: 1,
            pageList: [10, 20, 50, 100],//分页步进值
            search: true,//显示搜索框
            columns: [
                {
                    checkbox: true
                }, {
                    field: 'id',
                    title: 'id'
                }, {
                    field: 'name',
                    title: '名称'
                }, {
                    field: 'url',
                    title: '查询地址'
                }, {
                    field: 'created_at',
                    title: '创建时间'
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
@section('plugins.BootstrapTable',true)
