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

                {{-- With prepend slot --}}
                <x-adminlte-input name="name" label="name" placeholder="name" label-class="text-lightblue">
                </x-adminlte-input>

                {{-- With append slot, number type and sm size --}}
                <x-adminlte-input name="sku" label="Sku" placeholder="sku" type="text"
                                  igroup-size="sm" min=1 max=10>
                </x-adminlte-input>

                <x-adminlte-input name="category" label="分类" placeholder="分类" label-class="text-lightblue">
                </x-adminlte-input>
                {{-- With a link on the bottom slot and old support enabled --}}
                <x-adminlte-input name="iPostalCode" label="Postal Code" placeholder="postal code"
                                  enable-old-support>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-olive">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                    </x-slot>
                    <x-slot name="bottomSlot">
                        <a href="#">Search your postal code here</a>
                    </x-slot>
                </x-adminlte-input>

                {{-- With extra information on the bottom slot --}}
                <x-adminlte-input name="iExtraAddress" label="Other Address Data">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-purple">
                            <i class="fas fa-address-card"></i>
                        </div>
                    </x-slot>
                    <x-slot name="bottomSlot">
        <span class="text-sm text-gray">
            [Add other address information you may consider important]
        </span>
                    </x-slot>
                </x-adminlte-input>

                {{-- With multiple slots and lg size --}}
                <x-adminlte-input name="iSearch" label="Search" placeholder="search" igroup-size="lg">
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="outline-danger" label="Go!"/>
                    </x-slot>
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-danger">
                            <i class="fas fa-search"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </form>
        </x-adminlte-modal>
        {{-- Example button to open modal --}}
        <x-adminlte-button label="添加商品" data-toggle="modal" data-target="#modalPurple" class="bg-purple"/>
    </div>
    <div class="row">

    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
