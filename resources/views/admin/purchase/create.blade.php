@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>采购</h1>
@stop

@section('content')
    <div class="container">
        <div class="row ml-3 mt-3" style="display: block">
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
                                'maxDate' => "js:moment().endOf('month')",
                                'daysOfWeekDisabled' => [0, 6],
                            ];
                        @endphp
                        <x-adminlte-input-date name="deadline_at" label="采购截止时间" igroup-size="sm"
                                            value="{{ old('deadline_at') }}"  theme="info"  :config="$config" placeholder="Choose a working day...">
                            <x-slot name="appendSlot">
                                <div class="input-group-text bg-dark">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input-date>
                    </x-slot>
                    <div style="display: flex;flex-direction: row;">
                        <x-adminlte-input fgroup-class="col-md-6" value="{{ old('remark') }}" label="title" name="title" required
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

    <script>

        $(document).ready(function () {
            var counter = 0;

            function select2Init() {
                $('.js-data-example-ajax').select2({
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
                cols += ` <td><select  name="product_id[]" class="js-data-example-ajax form-control" required></select></td>`;
                cols += '<td><input type="text" class="form-control" name="price[]" required /></td>';
                cols += '<td><input type="number" min="1" class="form-control" name="quantity[]" required /></td>';
                cols += '<td><input type="text" class="form-control" name="explain[]" required /></td>';
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

@section('plugins.BsCustomFileInput', true)
@section('plugins.Summernote', true)
@section('plugins.Sweetalert2', true);
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true)
