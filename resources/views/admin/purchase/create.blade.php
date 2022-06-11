@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>新增采购订单</h1>
@stop

@section('content')

    <div class="container">
        <div class="row " style="display: block">
            <x-adminlte-card title="Form Card" theme="info" theme-mode="info"
                             class="elevation-3" body-class="bg-grey" header-class="bg-info"
                             footer-class="bg-info border-top rounded border-light"
                             icon="fas fa-lg fa-bell" collapsible removable maximizable >
                <x-slot name="toolsSlot">
                    @php
                        $config = [
                            'format' => 'YYYY-MM-DD HH.mm',
                            'dayViewHeaderFormat' => 'MMM YYYY',
                            'minDate' => "js:moment().startOf('month')",
                            'maxDate' => "js:moment().endOf('month')",
                            'daysOfWeekDisabled' => [0, 6],
                        ];
                    @endphp
                    <x-adminlte-input-date name="idSizeSm" label="采购截止时间" igroup-size="sm"
                                           :config="$config" placeholder="Choose a working day...">
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-date>
                </x-slot>
                <div style="display: flex;flex-direction: row;">
                    <x-adminlte-input  fgroup-class="col-md-6" label="title" name="title" required placeholder="请填写title"></x-adminlte-input>

                    <x-adminlte-select  fgroup-class="col-md-6" name="supplier" label="供应商">
                        <option value="1">aa公司</option>
                        <option value="2">b公司</option>
                        <option value="3">c公司</option>
                        <option value="add">创建</option>
                    </x-adminlte-select>
                </div>
                <x-adminlte-textarea label="备注" name="remark" ></x-adminlte-textarea>



                <x-slot name="footerSlot">
                    <x-adminlte-button class="d-flex ml-auto" theme="light" label="submit"
                                       icon="fas fa-sign-in"/>
                </x-slot>
            </x-adminlte-card>
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

    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.css">

@stop

@section('js')

    <script src="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.js"></script>
    <script src="/bootstrap-table-zh-CN.js"></script>

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
                var url = "{{ adminRoute('products.index') }}"
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
                    field: 'sku',
                    title: 'sku',
                    class: 'min-width-200'
                }, {
                    field: 'name',
                    title: 'name(jianjie1)',
                }, {
                    field: 'category',
                    title: '分类',
                }, {
                    field: 'brand',
                    title: '品牌',
                }, {
                    field: 'cover_img',
                    title: '封面图',
                    class: 'min-width-100',
                    formatter: imgFormatter,

                }, {
                    field: 'imgs',
                    title: '多图',
                    cardVisible: false,
                    formatter: imgsFormatter
                }, {
                    field: 'dl',
                    title: 'dl',
                }, {
                    field: 'dy',
                    title: 'dy',
                }, {
                    field: 'size',
                    title: 'size',
                }, {
                    field: 'bzq',
                    title: 'bzq'
                }, {
                    field: 'price_eu',
                    title: '欧元价格'
                }, {
                    field: 'price_us',
                    title: '美元价格'
                }, {
                    field: 'price_uk',
                    title: '英镑价格'
                }, {
                    field: 'price_jp',
                    title: '日元价格'
                }, {
                    field: 'status',
                    title: '状态'
                }, {
                    field: 'replace',
                    title: 'rep 可替换产品'
                }, {
                    field: 'description',
                    title: '描述'
                }, {
                    field: 'stock',
                    title: '库存'
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
        $('#table').bootstrapTable('hideColumn', ['imgs', 'size', 'replace', 'description', 'bzq', 'id']);
    </script>
@stop

@section('plugins.BsCustomFileInput', true)
@section('plugins.TempusDominusBs4', true)
{{--@section('plugins.Summernote', true)--}}
{{--@section('plugins.Sweetalert2', true);--}}
