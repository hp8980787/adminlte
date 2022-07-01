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
    <div class="row">
        <x-adminlte-modal id="shipping" size="md" theme="info" title="选择发货仓库" icon="fas fa-warehouse">
            <form action="{{ adminRoute('orders.shipping') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="order_id">
                <x-adminlte-select name="storehouse_id" label="选择仓库"></x-adminlte-select>
                <x-adminlte-button type="submit" theme="primary" label="提交"/>
            </form>
        </x-adminlte-modal>
    </div>
    <div class="row" style="display: block">
        <x-adminlte-modal id="purchase" title="采购" size="lg" width="100%" theme="teal" icon="fas fa-truck">
            <form action="{{ adminRoute('purchase.store') }}" method="post">
                @csrf
                <x-adminlte-card title="采购创建单" theme="info" theme-mode="info"
                                 size="lg" class="elevation-3" body-class="bg-grey" header-class="bg-info"
                                 footer-class="bg-info border-top rounded border-light"
                                 icon="fas fa-lg fa-bell" collapsible removable maximizable>
                    <x-slot name="toolsSlot">
                        @php
                            $config = [
                                'format' => 'YYYY-MM-DD HH.mm',
                                'dayViewHeaderFormat' => 'MMM YYYY',
                                'minDate' => "js:moment().startOf('month')",
                                'maxDate' => "js:moment().endOf('year')",
                                'daysOfWeekDisabled' => [0, 6],
                            ];
                        @endphp
                        <x-adminlte-input-date name="deadline_at" label="采购截止时间" igroup-size="sm"
                                               value="{{ old('deadline_at') }}" theme="info" :config="$config"
                                               placeholder="Choose a working day...">
                            <x-slot name="appendSlot">
                                <div class="input-group-text bg-dark">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input-date>
                    </x-slot>
                    <div style="display: flex;flex-direction: row;">
                        <x-adminlte-input fgroup-class="col-md-6" value="{{ old('title') }}" label="title" name="title"
                                          required
                                          placeholder="请填写title"></x-adminlte-input>
                        <x-adminlte-select fgroup-class="col-md-6" name="supplier_id" label="供应商">
                            <option value="1">aa公司</option>
                            <option value="2">b公司</option>
                            <option value="3">c公司</option>
                            <option value="add">创建</option>
                        </x-adminlte-select>
                    </div>
                    <x-adminlte-textarea label="备注" name="remark"></x-adminlte-textarea>
                    <div class="col-md-3 mt-3 mb-3">
                        <x-adminlte-button id="addrow" theme="info" label="添加列"></x-adminlte-button>
                    </div>
                    <table id="myTable" class=" table order-list">
                        <thead>
                        <tr>
                            <td>仓库(必须)</td>
                            <td>产品(必须)</td>
                            <td>采购价格(必须)</td>
                            <td>采购数量(必须)</td>
                            <td>说明</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        </tr>
                        </tbody>
                    </table>
                    <x-slot name="footerSlot">
                        <x-adminlte-button class="d-flex ml-auto" theme="light" type="submit" label="submit"
                                           icon="fas fa-sign-in"/>
                    </x-slot>
                </x-adminlte-card>
            </form>
        </x-adminlte-modal>
    </div>


@stop
@section('js')
    <script>
        //把订单产品和数据库产品链接
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
            let ids = data.map(function (v) {
                if (v.product_code && v.product_code != null) {
                    return v.id;
                }
            })
            ids = ids.filter(function (v) {
                return v && v != null
            })

            $.ajax({
                url: "{{ adminRoute('orders.link') }}",
                method: 'post',
                data: {
                    id: ids
                },
                async: false,
                success: function (res) {
                    let unFInd = res.unFind;
                    let rows = [];
                    for (let i in data) {
                        if (unFInd.indexOf(data[i].id) !== -1) {
                            rows.push(i)
                        }
                    }
                    $('#table').bootstrapTable('refresh');
                }
            })
        }

        //每行的样式
        function rowsLink(row, index) {
            var classes = [
                'bg-warning',
                '',
                '',
            ]
            return {
                classes: classes[row.link_status + 1],
            }

        }

        //每行子视图采购方法
        function purchase(id, sku) {
            console.log(id, sku);
            var cols = "";
            cols += `<td><select name="storehouse_id[]" class=" form-control">` + options + `</select></td>`;
            cols += ` <td><select  name="product_id[]" class="js-data-example-ajax form-control" value="${id}" required><option value="${id}">${sku}</option></select></td>`;
            cols += '<td><input type="text" class="form-control" name="price[]" required /></td>';
            cols += '<td><input type="number" min="1" class="form-control" name="quantity[]" required /></td>';
            cols += '<td><input type="text" class="form-control" name="explain[]"  /></td>';
            cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';

            $('#myTable').append(cols)
            $('#purchase').modal()
        }


        $('document').ready(function () {

            //行子视图
            function expendFormatter(value, row, index) {
                let html = '';
                html += `<table class="table " width="50%">`
                html += `<thead><th>sku</th> <th>pcode</th> <th>购买数量</th> <th>库存</th> <th>操作</th></thead>`
                html += `<tbody>`
                $.ajax({
                    url: "{{ adminRoute('orders.detail') }}",
                    method: 'get',
                    data: {
                        id: row.id
                    },
                    async: false,
                    success: function (res) {
                        for (let i in res) {
                            let product = res[i]
                            html += `<tr><th>${product.sku}</th><th>${product.pcode}</th><th>${product.pivot.quantity}</th>
<th>${product.stock}</th><th><a href="javascript:;"  onclick="purchase('${product.id}','${product.sku}')" title="采购"><i class="fas fa-truck"></i>采购</a></th>
    </tr>`
                        }
                    }
                })
                html += `</tbody>`
                html += `</table>`
                return html
            }

            //子视图过滤
            function detailFilter(value, row, index) {
                if (row.link_status === 1) return true
                return false
            }

            //汇率
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

            //操作栏样式格式化
            function operateFormatter(value, row, index) {
                let html = '';
                html += '<a class="edit" href="javascript:void(0)" title="edit">'
                html += '<i class="fas fa-edit"></i>'
                html += '</a>'
                if (row.is_shipping === 1) {
                    html += `<a class="shipping" href="javascript:void(0)" title="发货"><i class="fas fa-truck"></i> </a>`
                }
                return html
            }

            //操作栏事件
            window.operateEvents = {
                'click .shipping': function (e, value, row, index) {
                    $.ajax({
                        url: "{{ adminRoute('orders.warehouse') }}",
                        method: 'get',
                        data: {
                            id: row.id
                        },
                        async: false,
                        success: function (res) {
                            const data = res
                            const select = $('select[name="storehouse_id"]')
                            const id = $('#order_id')
                            let options = '';
                            // options += '<select class="select" name="storehouse_id" title="选择仓库">'
                            data.forEach(v => {
                                options += `<option value="${v.id}">${v.name}</option>`
                            })
                            id.val(row.id)
                            select.html(options)
                            $('#shipping').modal()

                        }
                    })

                }
            }

            function buyerInfoFormatter() {
                return "<a class='buyer_info bg-info' >点击查看</a>";
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
                detailView: true,
                detailFormatter: expendFormatter,
                detailFilter: detailFilter,
                rowStyle: rowsLink,
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

        //modal 采购方法
        function matchCustom(params, data) {
            // If there are no search terms, return all of the data
            if ($.trim(params.term) === '') {
                return data;
            }

            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null;
            }

            // `params.term` should be the term that is used for searching
            // `data.text` is the text that is displayed for the data object
            if (data.text.indexOf(params.term) > -1) {
                var modifiedData = $.extend({}, data, true);
                modifiedData.text += ' (matched)';

                // You can return modified objects from here
                // This includes matching the `children` how you want in nested data sets
                return modifiedData;
            }

            // Return `null` if the term should not be displayed
            return null;
        }

        $.ajax({
            url: "{{ adminRoute('supplier.index') }}",
            method: "get",
            success: (res) => {
                let options = [];
                for (let i in res) {
                    options += `<option value="${res[i].id}">${res[i].name}</option>`
                }
                $('#supplier_id').html(options)
            }
        })
        var options = '';
        $.ajax({
            url: "{{ adminRoute('storehouse.index') }}",
            method: 'get',
            async: false,
            success: (res) => {

                for (let i in res) {
                    options += `<option value="${i}">${res[i]}</option>`
                }
            }
        })

        var counter = 0;

        function select2Init() {
            $('.js-data-example-ajax').select2({
                matcher: matchCustom,
                dropdownAutoWidth: true,
                width: '200px',
                ajax: {
                    url: "{{ adminRoute('products.pagination') }}",
                    dataType: 'json',

                }
            });
        }


        $("#addrow").on("click", function () {
            var newRow = $("<tr>");
            var cols = "";
            cols += `<td><select name="storehouse_id[]" class=" form-control">` + options + `</select></td>`;
            cols += ` <td><select  name="product_id[]" class="js-data-example-ajax form-control" required></select></td>`;
            cols += '<td><input type="text" class="form-control" name="price[]" required /></td>';
            cols += '<td><input type="number" min="1" class="form-control" name="quantity[]" required /></td>';
            cols += '<td><input type="text" class="form-control" name="explain[]"  /></td>';
            cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
            newRow.append(cols);
            $("table.order-list").append(newRow);
            select2Init()
            counter++;
        });


        $("table.order-list").on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();
            counter -= 1
        });


        function calculateRow(row) {
            var price = +row.find('input[name^="price"]').val();

        }

        function calculateGrandTotal() {
            var grandTotal = 0;
            $("table.order-list").find('input[name^="price"]').each(function () {
                grandTotal += +$(this).val();
            });
            $("#grandtotal").text(grandTotal.toFixed(2));
        }

    </script>
@stop
@section('plugins.X-Editable',true)
@section('plugins.BootstrapTable',true)
@section('plugins.BsCustomFileInput',true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true)
