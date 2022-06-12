@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>采购</h1>
@stop

@section('content')
    <div class="container">
        <div class="row ml-3 mt-3" style="display: block">
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
                    <x-adminlte-input fgroup-class="col-md-6" label="title" name="title" required
                                      placeholder="请填写title"></x-adminlte-input>

                    <x-adminlte-select fgroup-class="col-md-6" name="supplier" label="供应商">
                        <option value="1">aa公司</option>
                        <option value="2">b公司</option>
                        <option value="3">c公司</option>
                        <option value="add">创建</option>
                    </x-adminlte-select>
                </div>
                <x-adminlte-textarea label="备注" name="remark"></x-adminlte-textarea>
                <div class="col-md-3 mt-3 mb-3">
                    <x-adminlte-button theme="info" onclick="addRow()" label="添加列"></x-adminlte-button>
                </div>
                <table id="table"></table>
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


        const data = [{
            id: `<x-adminlte-button  label="选择产品" igroup-size="sm"   theme="info"></x-adminlte-button>`,
            explain: `<x-adminlte-input name='explain'></x-adminlte-input>`,
            price: `<x-adminlte-input  name='price'> </x-adminlte-input>`,
            quantity: `<x-adminlte-input  type="number"  name='quantity'> </x-adminlte-input>`,
        }];

        function addRow() {
            data.push({
                id: `<x-adminlte-button label="选择产品" igroup-size="sm"   theme="info"></x-adminlte-button>`,
                explain: `<x-adminlte-input  name='explain'></x-adminlte-input>`,
                price: `<x-adminlte-input  name='price'> </x-adminlte-input>`,
                quantity: `<x-adminlte-input  type="number"  name='quantity'> </x-adminlte-input>`,
            });
            console.log(data);
            $("#table").bootstrapTable('refreshOptions', {
                data: data,
            });
        }

        window.onload = function () {
            $('#table').bootstrapTable({
                columns: [

                    {
                        field: 'id',
                        title: '产品',
                    }, {
                        field: 'explain',
                        title: '说明'
                    }, {
                        field: 'price',
                        title: '采购价格'
                    }, {
                        field: 'quantity',
                        title: '采购数量'
                    }],
                data: data
            });
        }

    </script>
@stop

@section('plugins.BsCustomFileInput', true)
@section('plugins.Summernote', true)
@section('plugins.Sweetalert2', true);
@section('plugins.TempusDominusBs4', true)
