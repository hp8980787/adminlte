@extends('adminlte::page')

@section('title', '供应商')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ adminRoute('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">供应商</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="container">
        <x-adminlte-button label="添加" data-target="#modalAdd" data-toggle="modal" theme="success" />
        <div class="row">

            <table id="table"></table>
        </div>
        <div class="row">
            <x-adminlte-modal id="modalAdd" title="添加供应商" size="lg" theme="teal"
                              icon="fas fa-bell" v-centered static-backdrop scrollable>
                <div class="form">
                    <form action="{{ adminRoute('supplier.store') }}" method="POST">
                        @csrf
                        <x-adminlte-input label="name" name="name" value="{{ old('name') }}" required></x-adminlte-input>
                        <x-adminlte-input label="电话号码" name="phone" value="{{ old('phone') }}" required></x-adminlte-input>
                        <x-adminlte-input label="邮箱" name="email" type="email" required></x-adminlte-input>
                        <x-adminlte-input label="公司网址" name="web"></x-adminlte-input>
                        <x-adminlte-textarea label="公司地址" name="address"></x-adminlte-textarea>
                        <x-adminlte-button type="submit" class="mr-auto" theme="success" label="提交"/>
                    </form>
                </div>
                <x-slot name="footerSlot">


                </x-slot>
            </x-adminlte-modal>
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
        .bootstrap-duallistbox-container select {
            height: 400px !important;
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
                '</a>',
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
            },

        }

        $('#table').bootstrapTable({
            ajax: function (params) {
                var url = "{{ adminRoute('supplier.index') }}"
                $.get(url + '?' + $.param(params.data)).then(function (res) {
                    const data = res.data;
                    console.log(data)
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
            // showColumns: true,
            showRefresh: true,
            pagination: true,//分页
            sidePagination: 'server',//服务器端分页
            pageNumber: 1,
            pageList: [10, 20, 50, 100],//分页步进值
            search: false,//显示搜索框
            columns: [
                {
                    checkbox: true
                },
                {
                    field: 'id',
                    title: 'id',
                    class: 'min-width-100'
                }, {
                    field: 'name',
                    title: 'name',
                    class: 'min-width-200',
                }, {
                    field: 'phone',
                    title: '电话'
                }, {
                    field: 'email',
                    title: '邮箱',
                },{
                    field: 'address',
                    title: '地址',
                },{
                    field: 'web',
                    title: '网址',
                },{
                    field: 'operate',
                    title: '操作',
                    align: 'center',
                    clickToSelect: false,
                    class: 'min-width-200',
                    events: window.operateEvents,
                    formatter: operateFormatter
                }
            ]
        })

    </script>
@stop


@section('plugins.BootstrapTable',true)
