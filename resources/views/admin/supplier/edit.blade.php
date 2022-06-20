@extends('adminlte::page')

@section('title', '供应商edit')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ adminRoute('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ adminRoute('supplier.index') }}">供应商</a></li>
            <li class="breadcrumb-item active" aria-current="page">edit</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="container">


            <form action="{{ adminRoute('supplier.update',$supplier->id) }}" method="POST">
                @csrf
                @method('PUT')
                <x-adminlte-input label="name" name="name" value="{{ old('name',$supplier->name) }}" required></x-adminlte-input>
                <x-adminlte-input label="电话号码" name="phone" value="{{ old('phone',$supplier->phone) }}" required></x-adminlte-input>
                <x-adminlte-input label="邮箱" name="email" type="email"  value="{{ old('email',$supplier->email) }}" required></x-adminlte-input>
                <x-adminlte-input label="公司网址" name="web" value="{{ old('web',$supplier->web) }}"></x-adminlte-input>
                <x-adminlte-textarea label="公司地址" name="address">{{ old('address',$supplier->address) }}</x-adminlte-textarea>
                <x-adminlte-button type="submit" class="mr-auto" theme="success" label="提交"/>
            </form>

    </div>


@stop

@section('plugins.Sweetalert2', true)

