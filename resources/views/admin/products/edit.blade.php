@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>商品信息</h1>
@stop

@section('content')
    <div class="row">
        <a href="{{ adminRoute('products.index') }}">
            <x-adminlte-button type="info" label="返回"></x-adminlte-button>
        </a>

    </div>
    <div class="container">

        <div class="row">

            <form action="{{ adminRoute('products.update',[$product->id])}}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                {{-- With prepend slot --}}
                <x-adminlte-input value="{{ old('name',$product->name) }}" name="name" label="name(jianjie1) 必须"
                                  placeholder="name"
                                  required="required"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                {{-- With append slot, number type and sm size --}}
                <x-adminlte-input value="{{ old('sku',$product->sku) }}" name="sku" label="Sku(必须)" placeholder="sku"
                                  type="text">
                    <x-slot name="appendSlot">
                        <x-adminlte-button onClick="generateSku()" theme="success" label="生成"/>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('category',$product->category) }}" name="category" label="分类(必须)"
                                  required="required"
                                  placeholder="分类"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('brand',$product->brand) }}" name="brand" label="brand(必须)"
                                  required="required"
                                  placeholder="分类"
                                  label-class="text-lightblue">
                </x-adminlte-input>
                <img src="/{{ $product->cover_img }}" alt="">
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

                <x-adminlte-input value="{{ old('dl',$product->dl) }}" name="dl" label="dl(必须)" required="required"
                                  placeholder="dl"
                                  label-class="text-lightblue">
                </x-adminlte-input>
                <x-adminlte-input value="{{ old('dy',$product->dy) }}" name="dy" label="dy(必须)" required="required"
                                  placeholder="dy"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('size',$product->size) }}" name="size" label="size" placeholder="size"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('bzq',$product->bzq) }}" name="bzq" label="bzq" placeholder="bzq"
                                  label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('price_eu',$product->price_eu) }}" name="price_eu" label="欧元价格"
                                  placeholder="欧元价格"
                                  label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input value="{{ old('price_us',$product->price_us) }}" name="price_us" label="美元价格"
                                  placeholder="美元价格"
                                  label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('price_uk',$product->price_uk) }}" name="price_uk" label="英镑价格"
                                  placeholder="英镑价格"
                                  label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-pound-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('price_jp',$product->price_jp) }}" name="price_jp" label="日元价格"
                                  placeholder="日元价格"
                                  label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-yen-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>

                <x-adminlte-input value="{{ old('stock',$product->stock) }}" name="stock" type="number" min="0"
                                  label="库存" >
                </x-adminlte-input>
                <x-adminlte-textarea value="{{ old('replace',$product->replace) }}" name="replace" label="替换品(必须)"
                                     placholder="用空格分割"
                                     required="required"
                                     label-class="text-lightblue">
                    {{ old('replace',$product->replace) }}
                </x-adminlte-textarea>
                @php
                    $config=[
                     "height" => "200",
                    ];
                @endphp
                <x-adminlte-text-editor name="description"  :config="$config" igroup-size="lg" label="描述(必须)">
                    {{ old('description',$product->description) }}
                </x-adminlte-text-editor>

                <x-adminlte-button type="submit" label="提交" theme="success"/>
            </form>

        </div>
    </div>


@stop



@section('js')

@stop

@section('plugins.BsCustomFileInput', true)
@section('plugins.Summernote', true)

