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

    <div class="row">
        <div class="col-md-12">
            <x-adminlte-button label="create" data-toggle="modal" data-target="#modalAdd" theme="success"/>
            <x-adminlte-button label="导入" data-toggle="modal" data-target="#modalImport" theme="info"
                               icon="fas fa-file-import"/>
            <x-adminlte-button onClick="link()" theme="primary" label="产品关联" icon="fas fa-link"/>
        </div>
    </div>
    <div class="row">
        <div class="table-responsive">
            <table id="table" data-editable="true" data-editable-emptytext="product_code">
            </table>
        </div>
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
        <x-adminlte-modal title="用户信息" id="payer_info" size="lg" theme="teal" icon="fas fa-user">
            <div class="body"></div>
        </x-adminlte-modal>
    </div>

@stop
@section('js')
    <script>
        function link() {
            const data = $('#table').bootstrapTable('getSelections')
            if (data.length == 0) {
                Swal.fire({
                    position: 'top-start',
                    icon: 'error',
                    title: '所选产品不能为空!',
                    showConfirmButton: false,
                    timer: 1800
                })
                return false;
            }
            let  ids = data.map(function (v) {
                if (v.product_code&&v.product_code!=null) {
                    console.log(v)
                    return v.id;
                }
            })
            ids = ids.filter(function (v){
                console.log(v)
                return v && v!=null
            })
            console.log(ids)
            $.ajax({
                url: "{{ adminRoute('orders.link') }}",
                method: 'post',
                data: {
                    id: ids
                },
                async: false,
                success: function (res) {
                    console.log(res)
                }
            })
        }

        $('document').ready(function () {


            function rate(total, currency) {
                $.ajax({
                    url: "{{ adminRoute('orders.index') }}",
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

            function operateFormatter(value, row, index) {
                return [
                    '<a class="edit" href="javascript:void(0)" title="edit">',
                    '<i class="fas fa-edit"></i>',
                    '</a>  ',
                    // '<a class="remove" href="javascript:void(0)" title="Remove">',
                    // '<i class="fa fa-trash"></i>',
                    // '</a>',
                    '<a class="permissions" data-toggle="modal" data-target="#modalPermission" href="javascript:;" title="permissions">',
                    '<i class="fas fa-key"></i>',
                    '</a>'
                ].join('')
            }

            function buyerInfoFormatter() {
                return "<a class='buyer_info'>点击查看</a>";
            }

            function pcodeSave(value) {
                alert(value)
            }

            window.buyerInfoEvent = {
                'click .buyer_info': function (e, value, row, index) {
                    let html = `<ul class="list-group">`;
                    for (let i in value) {
                        html += `<li class="list-group-item">${i} : ${value[i]}</li>`
                    }
                    html += '</ul>'
                    const payer_info = $('#payer_info')
                    payer_info.attr('title', 111)
                    payer_info.find('.body').html(html)
                    payer_info.modal();
                }
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
                    console.log(params);
                    return {
                        perPage: params.pageSize,   //页面大小
                        search: params.searchText, //搜索
                        sortOrder: params.sortOrder, //排序
                        sortName: params.sortName, //排序
                        page: params.pageNumber,

                    };
                },
                clickToSelect: true,
                showHeader: true,
                showColumns: true,
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
                        field: 'order_number',
                        title: '本地交易id'
                    }, {
                        field: 'total',
                        title: '总价',
                        sortable: true,
                    }, {
                        field: 'total_usd',
                        title: '总价美元',
                        sortable: true,
                    }, {
                        field: 'currency',
                        title: '货币单位',

                    }, {
                        field: 'buyer_info',
                        title: '买家信息',
                        formatter: buyerInfoFormatter,
                        events: window.buyerInfoEvent
                    }, {
                        field: 'description',
                        title: '购买内容',
                    }, {
                        field: 'product_code',
                        title: '产品pcode',
                        editable: function (value, row, index) {
                            return {
                                type: 'text',
                                validate: function (value) {
                                    console.log(value)
                                    if ($.trim(value) == '') {
                                        return 'This field is required';
                                    }

                                    if (!value.includes(',')) {
                                        return '格式错误'
                                    }
                                    let array = value.split(',')
                                    let reg = new RegExp("([\w+\-\+\_\.\#\@\&\*\(\)\|\,]+\|\d+)", "g");
                                    array = array.filter(function (s) {
                                        return s && s.trim();
                                    })
                                    for (let i in array) {
                                        if (!array[i].match(reg)) {
                                            return '格式错误'
                                        }
                                    }
                                },
                                url: "{{ adminRoute('orders.editable') }}",
                                params: function (params) {
                                    params.id = row.id
                                    return params
                                },
                                ajaxOptions: {
                                    type: 'put',
                                    dataType: 'json'
                                },
                                success: function (response, newValue) {
                                    if (!response.success) return response.msg;
                                    window.Toaset.fire({
                                        icon: 'success',
                                        title: '成功'
                                    })
                                    $table.bootstrapTable('refresh')
                                }
                            }
                        }


                    }, {
                        field: 'created_at',
                        title: '创建时间',
                    }, {
                        field: 'operate',
                        title: '操作',
                        align: 'center',
                        clickToSelect: false,
                        events: window.operateEvents,
                        formatter: operateFormatter
                    }
                ]
            })
            // $table.bootstrapTable('hideColumn', ['id'])

            $(window).resize(function () {
                $table.bootstrapTable('resetView')
            })

        })
    </script>
@stop
@section('plugins.X-Editable',true)
@section('plugins.BootstrapTable',true)
@section('plugins.BsCustomFileInput',true)
