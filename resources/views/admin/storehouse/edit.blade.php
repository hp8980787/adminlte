@extends('adminlte::page')
@section('title','storehouse 编辑 '.$storehouse->name)

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ adminRoute('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ adminRoute('storehouse.index') }}">仓库</a></li>
            <li class="breadcrumb-item active" aria-current="page">edit</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="container">
        <form action="{{ adminRoute('storehouse.update',$storehouse->id) }}" method="POST">
            @csrf
            @method('put')
            <x-adminlte-input label="name" name="name" value="{{ old('name',$storehouse->name) }}" required></x-adminlte-input>
            <x-adminlte-button label="提交" theme="success" type="submit" />
        </form>
    </div>

@stop
