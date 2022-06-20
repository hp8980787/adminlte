@extends('adminlte::page')

@section('title', '权限修改')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ adminRoute('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ adminRoute('permissions.index') }}">权限</a></li>
            <li class="breadcrumb-item active" aria-current="page">edit</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <form action="{{ adminRoute('permissions.update',$permission->id) }}" method="POST">
                @csrf
                @method('put')
                <x-adminlte-input label="name" name="name" value="{{ old('name',$permission->name) }}"></x-adminlte-input>
                <x-adminlte-button type="submit" label="submit"></x-adminlte-button>
            </form>
        </div>
    </div>

@stop




