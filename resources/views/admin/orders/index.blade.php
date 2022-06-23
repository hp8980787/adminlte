@extends('adminlte::page')
@section('title','订单')
@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ adminRoute('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">订单</li>
        </ol>
    </nav>
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <x-adminlte-button label="create" data-toggle="modal" data-target="#modalAdd" theme="success"/>
                <x-adminlte-button label="导入" data-toggle="modal" data-target="#modalImport" theme="info"
                                   icon="fas fa-file-import"/>
            </div>
        </div>
        <div class="row">
            <table id="table">

            </table>
        </div>
        <div class="row">
            <x-adminlte-modal id="modalAdd" size="lg" scrollable theme="teal" icon="fas fa-shopping-bag">
                <form action="{{ adminRoute('orders.store') }}" method="POST">
                    @csrf
                    <x-adminlte-input name="trans_id" label="trans_id" required></x-adminlte-input>
                    <x-adminlte-input name="order_number" label="order_number" required></x-adminlte-input>
                    <x-adminlte-input name="total" label="总价" required></x-adminlte-input>
                    <x-adminlte-select name="currency" label="货币单位" required>
                        <option value="">请选择</option>
                        <option value="EUR">EUR</option>
                        <option value="JPY">JPY</option>
                        <option value="USD">USD</option>
                        <option value="GBP">GBP</option>
                        <option value="SGD">SGD</option>
                    </x-adminlte-select>
                    <x-adminlte-input name="total_usd" label="总价" disabled="" required></x-adminlte-input>
                    <x-adminlte-input name="name" label="姓名" required></x-adminlte-input>
                    <x-adminlte-input name="phone" label="电话" required></x-adminlte-input>
                    <x-adminlte-input name="email" type="email" label="email" required></x-adminlte-input>
                    <x-adminlte-input name="postal" label="邮编" required></x-adminlte-input>
                    <x-adminlte-input name="country" label="国家" required></x-adminlte-input>
                    <x-adminlte-input name="state" label="state" required></x-adminlte-input>
                    <x-adminlte-input name="city" label="city" required></x-adminlte-input>
                    <x-adminlte-input name="street1" label="street1" required></x-adminlte-input>
                    <x-adminlte-input name="street2" label="street2"></x-adminlte-input>
                    <x-adminlte-input name="product_code" label="pcode"></x-adminlte-input>
                    <x-adminlte-input name="description" label="description"></x-adminlte-input>

                </form>
            </x-adminlte-modal>
        </div>
        <div class="row">
            <x-adminlte-modal id="modalImport" size="lg" theme="info" icon="fas fa-file-import">
                <form id="orders-import" action="{{ adminRoute('orders.import') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <x-adminlte-input-file name="file" igroup-size="sm" placeholder="仅支持 xml csv excel">
                        <x-slot name="prependSlot">
                            <div class="input-group-text bg-lightblue">
                                <i class="fas fa-upload"></i>
                            </div>
                        </x-slot>
                    </x-adminlte-input-file>
                </form>
                <x-slot name="footerSlot">
                    <x-adminlte-button onClick="document.getElementById('orders-import').submit()" theme="success"
                                       type="submit" label="提交"/>
                </x-slot>
            </x-adminlte-modal>
        </div>
    </div>
@stop
@section('js')
    <script>
        $('document').ready(function () {
            function rate(total, currency) {
                $.ajax({
                    url: "{{ adminRoute('rate.select') }}",
                    method: "get",
                    async: false,
                    data: {
                        price: total,
                        currency: currency,
                    },
                    success: function (res) {
                        console.log(res)
                        $('input[name="total_usd"]').val(res.data)
                    }
                })
            }

            const totalInput = $('input[name="total"]')
            const currencyInput = $('select[name="currency"]')
            totalInput.change(function () {
                let total = totalInput.val()
                let currency = $('select[name="currency"]').val()
                if (currency) {
                    rate(total, currency)
                }
            })
            currencyInput.change(function () {
                let total = totalInput.val()
                let currency = currencyInput.val()
                if (total) {
                    rate(total, currency)
                }
                return false;
            })
            const $table = $('#table');
            $table.bootstrapTable({
                ajax: function (params) {
                    var url = "{{ adminRoute('orders.index') }}"
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
                showColumns: true,
                // hideColumn: ['sku'],
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
                        field: 'trans_id',
                        title: '交易id',
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
        })
    </script>
@stop
@section('plugins.BootstrapTable',true)
@section('plugins.BsCustomFileInput',true)
