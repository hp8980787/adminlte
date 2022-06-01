@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>商品信息</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-6 offset-lg-3">

            @if ($errors->any())
                <x-adminlte-alert theme="danger" title="error" dismissable>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-adminlte-alert>
            @endif

        </div>

    </div>
    <div class="row">

        {{-- Themed --}}
        <x-adminlte-modal id="modalPurple" title="添加商品" theme="purple" icon="fas fa-bolt" size='lg' disable-animations>

            <form action="{{ adminRoute('products.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                {{-- With prepend slot --}}
                <x-adminlte-input value="{{ old('name') }}" name="name" label="name(jianjie1) 必须" placeholder="name" required="required"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                {{-- With append slot, number type and sm size --}}
                <x-adminlte-input value="{{ old('sku') }}" name="sku" label="Sku(必须)"  placeholder="sku" type="text">
                    <x-slot name="appendSlot">
                        <x-adminlte-button onClick="generateSku()" theme="success" label="生成"/>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('category') }}" name="category" label="分类(必须)" required="required" placeholder="分类"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('brand') }}" name="brand" label="brand(必须)" required="required" placeholder="分类"
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

                <x-adminlte-input value="{{ old('size') }}" name="size" label="size" required="required" placeholder="size"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('bzq') }}" name="bzq" label="bzq" placeholder="bzq" label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('price_eu') }}" name="price_eu" label="欧元价格" placeholder="欧元价格" label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input value="{{ old('price_us') }}"  name="price_us" label="美元价格" placeholder="美元价格" label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('price_uk') }}" name="price_uk" label="英镑价格" placeholder="英镑价格" label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-pound-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('price_jp') }}" name="price_jp" label="日元价格" placeholder="日元价格" label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-yen-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('stock') }}" name="stock" type="number" label="库存" value="0">
                </x-adminlte-input>
                <x-adminlte-textarea value="{{ old('replace') }}" name="replace" label="替换品(必须)" placholder="用空格分割" required="required" placeholder="分类"
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
    <div class="row">

    </div>
@stop

@section('css')

@stop

@section('js')
    <script>
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
