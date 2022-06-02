@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>商品信息</h1>
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

                <x-adminlte-input value="{{ old('stock') }}" name="stock" type="number" min="0" label="库存" value="0">
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
    {{--    <div class="row">--}}
    {{--        <div class="col-lg-12 col-sm-12">--}}
    {{--            @php--}}
    {{--                $heads = [--}}
    {{--                    'ID',--}}
    {{--                    'Name',--}}
    {{--                    ['label' => 'Phone', 'width' => 40],--}}
    {{--                    ['label' => 'Actions', 'no-export' => true, 'width' => 5],--}}
    {{--                ];--}}

    {{--                $btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">--}}
    {{--                                <i class="fa fa-lg fa-fw fa-pen"></i>--}}
    {{--                            </button>';--}}
    {{--                $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">--}}
    {{--                                  <i class="fa fa-lg fa-fw fa-trash"></i>--}}
    {{--                              </button>';--}}
    {{--                $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">--}}
    {{--                                   <i class="fa fa-lg fa-fw fa-eye"></i>--}}
    {{--                               </button>';--}}

    {{--                $config = [--}}
    {{--                    'data' => [--}}
    {{--                        [22, 'John Bender', '+02 (123) 123456789', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],--}}
    {{--                        [19, 'Sophia Clemens', '+99 (987) 987654321', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],--}}
    {{--                        [3, 'Peter Sousa', '+69 (555) 12367345243', '<nobr>'.$btnEdit.$btnDelete.$btnDetails.'</nobr>'],--}}
    {{--                    ],--}}
    {{--                    'order' => [[1, 'asc']],--}}
    {{--                    'columns' => [null, null, null, ['orderable' => false]],--}}
    {{--                ];--}}
    {{--            @endphp--}}

    {{--            --}}{{-- Minimal example / fill data using the component slot --}}
    {{--            <x-adminlte-datatable id="table1" :heads="$fields" pageing="false" with-buttons>--}}
    {{--                @foreach($data as $row)--}}
    {{--                    <tr>--}}
    {{--                        @foreach($row as $cell)--}}
    {{--                            <td>{!! $cell !!}</td>--}}
    {{--                        @endforeach--}}
    {{--                        <td>--}}
    {{--                            1111--}}
    {{--                        </td>--}}
    {{--                    </tr>--}}
    {{--                @endforeach--}}
    {{--            </x-adminlte-datatable>--}}


    {{--            <div class="page mt-3" >--}}
    {{--              {{ $products->links() }}--}}
    {{--          </div>--}}
    {{--        </div>--}}

    {{--    </div>--}}
    <div class="row">
        <div class="col-lg-12">
            <table id="table_id" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    @foreach($fields as $field)
                        <th>{{ $field }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>


                </tbody>
                {{--                <tfoot>--}}
                {{--                @foreach($fields as $field)--}}
                {{--                    <th>{{ $field }}</th>--}}
                {{--                @endforeach--}}
                {{--                </tfoot>--}}
            </table>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
    <script>

        $(document).ready(function () {
            $('#table_id').DataTable({
                paging: true,
                scrollY: 400,
                ajax: {
                    url: "{{ adminRoute('products.index') }}",

                    "dataSrc": "",
                },
                columns: [
                    {data: 'id'},
                    {data: 'sku'},
                    {data: 'name'},
                    {data: 'category'},
                    {data: 'brand'},
                    {data: 'cover_img'},
                    {data: 'imgs'},
                    {data: 'dl'},
                    {data: 'dy'},
                    {data: 'type '},
                    {data: 'size'},
                    {data: 'bzq'},
                    {data: 'price_eu'},
                    {data: 'price_us'},
                    {data: 'price_uk'},
                    {data: 'price_jp'},
                    {data: 'status'},
                    {data: 'replace'},
                    {data: 'description'},
                    {data: 'stock'},
                    {data: 'created_at'},
                    {data: 'updated_at'},


                ],

            });
        });

        function generateSku() {
            $.ajax({
                method: 'get',
                url: "{{ adminRoute('sku') }}",
                data: {},
                success: function (response) {
                    $("input[name='sku']").val(response);
                }
            })
        }
    </script>
    <script> console.log('Hi!'); </script>
@stop

@section('plugins.BsCustomFileInput', true)
@section('plugins.Summernote', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
