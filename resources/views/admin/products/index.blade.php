@extends('adminlte::page')

@section('title', '商品信息')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ adminRoute('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">商品信息</li>
        </ol>
    </nav>
@stop

@section('content')

    <div class="row">
        {{-- Themed --}}
        <x-adminlte-modal id="modalPurple" title="添加商品" theme="purple" icon="fas fa-bolt" size='lg' disable-animations>

            <form action="{{ adminRoute('products.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                {{-- With prepend slot --}}
                <x-adminlte-input value="{{ old('name') }}" name="name" label="name(jianjie1) 必须" placeholder="name"
                                  required="required"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                {{-- With append slot, number type and sm size --}}
                <x-adminlte-input value="{{ old('sku') }}" name="sku" label="Sku(必须)" placeholder="sku" type="text">
                    <x-slot name="appendSlot">
                        <x-adminlte-button onClick="generateSku()" theme="success" label="生成"/>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('category') }}" name="category" label="分类(必须)" required="required"
                                  placeholder="分类"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('brand') }}" name="brand" label="brand(必须)" required="required"
                                  placeholder="分类"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input-file name="cover_img" label="封面图(必须)" placeholder="Choose a file..."
                                       disable-feedback/>
                <x-adminlte-input-file id="ifMultiple" name="ifMultiple[]" label="多图(必须)"
                                       placeholder="Choose multiple files..." igroup-size="lg" legend="Choose" multiple>
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="primary" label="Upload"/>
                    </x-slot>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-file-upload"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-file>

                <x-adminlte-input value="{{ old('dl') }}" name="dl" label="dl(必须)" required="required" placeholder="dl"
                                  label-class="text-lightblue">
                </x-adminlte-input>
                <x-adminlte-input value="{{ old('dy') }}" name="dy" label="dy(必须)" required="required" placeholder="dy"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('size') }}" name="size" label="size" placeholder="size"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('bzq') }}" name="bzq" label="bzq" placeholder="bzq"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('price_eu') }}" name="price_eu" label="欧元价格" placeholder="欧元价格"
                                  label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input value="{{ old('price_us') }}" name="price_us" label="美元价格" placeholder="美元价格"
                                  label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('price_uk') }}" name="price_uk" label="英镑价格" placeholder="英镑价格"
                                  label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-pound-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('price_jp') }}" name="price_jp" label="日元价格" placeholder="日元价格"
                                  label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-yen-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('stock') }}" name="stock" type="number" min="0" label="库存">
                </x-adminlte-input>
                <x-adminlte-textarea value="{{ old('replace') }}" name="replace" label="替换品(必须)" placholder="用空格分割"
                                     required="required"
                                     label-class="text-lightblue">
                </x-adminlte-textarea>
                <x-adminlte-text-editor name="description" label="描述(必须)">
                    {{ old('description') }}
                </x-adminlte-text-editor>

                <x-adminlte-button type="submit" label="提交" theme="success"/>
            </form>
        </x-adminlte-modal>

        {{-- Example button to open modal --}}
        <x-adminlte-button label="添加商品" data-toggle="modal" data-target="#modalPurple" class="bg-purple"/>

    </div>
    <div class="row mt-3">
        <div class="table-responsive">
            <table data-height="800" data-show-columns="true" id="table">
            </table>
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
            return `<img width="100px" src="${value}">`;
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
                    field: 'jianjie1',
                    title: 'name(jianjie1)',
                },{
                  field: 'jianjie2',
                  title: 'jianjie2'
                },{
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
        $('#table').bootstrapTable('hideColumn', ['imgs', 'size', 'replace', 'description', 'bzq', 'id','jianjie2']);
    </script>
@stop

@section('plugins.BsCustomFileInput', true)
@section('plugins.Summernote', true)
@section('plugins.BootstrapTable',true)
