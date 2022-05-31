@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>商品信息</h1>
@stop

@section('content')
    <div class="row">
        {{-- Themed --}}
        <x-adminlte-modal id="modalPurple" title="添加商品" theme="purple" icon="fas fa-bolt" size='lg' disable-animations>
            <form action="{{ adminRoute('products.store')}}" method="post">
                @csrf
                {{-- With prepend slot --}}
                <x-adminlte-input name="name" label="name" placeholder="name" label-class="text-lightblue">
                </x-adminlte-input>

                {{-- With append slot, number type and sm size --}}
                <x-adminlte-input name="sku" label="Sku" placeholder="sku" type="text"
                                  igroup-size="sm" min=1 max=10>
                </x-adminlte-input>

                <x-adminlte-input name="category" label="分类" placeholder="分类" label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input-file name="cover_img" label="Upload file" placeholder="Choose a file..."
                                       disable-feedback/>
                <x-adminlte-input-file id="ifMultiple" name="ifMultiple[]" label="Upload files"
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

                <x-adminlte-input name="dl" label="dl" placeholder="dl" label-class="text-lightblue">
                </x-adminlte-input>
                <x-adminlte-input name="dy" label="dy" placeholder="dy" label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input name="size" label="size" placeholder="size" label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input name="bzq" label="bzq" placeholder="bzq" label-class="text-lightblue">
                </x-adminlte-input>

                <x-adminlte-input name="price_eu" label="欧元价格" placeholder="欧元价格" label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>


                <x-adminlte-button type="submit" label="Success" theme="success"  />
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
    <script> console.log('Hi!'); </script>
@stop

@section('plugins.BsCustomFileInput', true)
