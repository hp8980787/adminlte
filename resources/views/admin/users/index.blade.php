@extends('adminlte::page')

@section('title', '用户')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ adminRoute('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">用户</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table id="table"></table>
            </div>
        </div>
        <div class="row">
            <x-adminlte-modal id="modalCustom" title="分配角色" size="lg" theme="teal"
                              icon="fas fa-users" v-centered static-backdrop scrollable>
                <div>
                    <input type="hidden" value="" name="user_id">
                    <select multiple="multiple" size="10" name="roles[]">

                    </select>
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button theme="info" onclick="rolesSubmit()" label="submit" data-dismiss="modal"/>
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
    </style>

@stop

@section('js')
    <script>

        function rolesSubmit() {
            const user_id = $('input[name="user_id"]').val();
            const roles = $('select[name="roles[]"]').val();
            $.ajax({
                url: "{{ adminRoute('users.assign-roles') }}",
                method: 'put',
                data: {
                    user_id:user_id,
                    roles:roles
                },
                success:(response)=>{
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: '分配成功',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    setInterval(function (){
                        window.location.href="{{ adminRoute('users.index') }}"
                    },1000)

                }
            })
        }


        function operateFormatter(value, row, index) {
            return [
                '<a class="edit" href="javascript:void(0)" title="edit">',
                '<i class="fas fa-edit"></i>',
                '</a>  ',
                '<a class="remove" href="javascript:void(0)" title="Remove">',
                '<i class="fa fa-trash"></i>',
                '</a>',
                '<a class="roles"   data-toggle="modal" data-target="#modalCustom"  href="javascript:;" title="roles">',
                '<i class="fas fa-users"></i>',
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
            'click .roles': function (e, value, row, index) {
                let id = row.id
                let roles = row.roles;
                $("input[name='user_id']").val(id)
                $.ajax({
                    url: "{{ adminRoute('roles.all') }}",
                    method: 'get',
                    success: (response) => {
                        let options = '';
                        for (let i in response) {
                            console.log(roles.indexOf(response[i]));
                            if (roles.indexOf(response[i]) != -1) {
                                options += `<option value="${i}" selected>${response[i]}</option>`
                            } else {
                                options += `<option value="${i}">${response[i]}</option>`
                            }
                        }
                        $('select[name="roles[]"]').html(options)
                        var dualListContainer = $('select[name="roles[]"]')
                        dualListContainer.bootstrapDualListbox({
                            filterTextClear: '角色',
                            moveAllLabel: '移动所有',
                            iconsPrefix: 'fas',
                            iconMove: 'fa-user'
                        });
                        dualListContainer.bootstrapDualListbox('refresh')
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
                var url = "{{ adminRoute('users.index') }}"
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
            search: true,//显示搜索框
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
                    field: 'email',
                    title: '邮箱'
                }, {
                    field: 'roles',
                    title: '角色'
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

@section('plugins.BsCustomFileInput', true)
@section('plugins.Summernote', true)
@section('plugins.Listbox',true)
@section('plugins.BootstrapTable',true)
