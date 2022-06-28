@extends('adminlte::page')

@section('title', '采购')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ adminRoute('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ adminRoute('purchase.index') }}">采购</a></li>
            <li class="breadcrumb-item active" aria-current="page">create</li>
        </ol>
    </nav>
@stop

@section('content')

        <div class="row " style="display: block">
            <form action="{{ adminRoute('purchase.store') }}" method="post">
                @csrf
                <x-adminlte-card title="Form Card" theme="info" theme-mode="info"
                                 class="elevation-3" body-class="bg-grey" header-class="bg-info"
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


        </div>



@stop



@section('js')

    <script>

        $(document).ready(function () {
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
                cols +=`<td><select name="storehouse_id[]" class=" form-control">`+options+`</select></td>`;
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

        function operateFormatter(value, row) {
            return `<a class="remove" href="javascript:void(0)" title="Remove">
      <i class="fa fa-trash"></i>
      </a>`;
        }


    </script>

@stop

@section('plugins.Sweetalert2', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true)
