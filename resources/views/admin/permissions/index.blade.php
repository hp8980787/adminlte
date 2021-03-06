@extends('adminlte::page')

@section('title', '权限')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ adminRoute('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">权限</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="container">
        <div class="row ml-3">
            <div class="col-md-12">
                <x-adminlte-button label="添加权限" theme="info" data-toggle="modal"
                                   data-target="#modalCreate"></x-adminlte-button>
            </div>
            <table id="table"></table>
        </div>
        <div class="row">
            <form action="{{ adminRoute('permissions.store') }}" method="post">
                <x-adminlte-modal id="modalCreate" title="添加权限" size="lg" theme="teal"
                                  icon="fas fa-bell" v-centered static-backdrop>
                    <div class="body">

                        @csrf
                        <x-adminlte-input label="name" name="name" required></x-adminlte-input>
                        <x-adminlte-input value="web" type="hidden" name="guard_name" required>
                        </x-adminlte-input>

                    </div>
                    <x-slot name="footerSlot">
                        <x-adminlte-button class="mr-auto" type="submit" theme="success" label="submit"/>

                    </x-slot>
                </x-adminlte-modal>
            </form>
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
                '</a>',

            ].join('')
        }

        window.operateEvents = {
            'click .edit': function (e, value, row, index) {
                window.location.href = row['editUrl'];
            },
            'click .remove': function (e, value, row, index) {
                console.log(row.delUrl)
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

        $('#table').bootstrapTable({
            ajax: function (params) {
                var url = "{{ adminRoute('permissions.index') }}"
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
                }, {
                    field: 'name',
                    title: 'name',
                }, {
                    field: 'guard_name',
                    title: 'guard_name'
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
