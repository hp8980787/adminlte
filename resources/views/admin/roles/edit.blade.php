@extends('adminlte::page')

@section('title', '角色 edit')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ adminRoute('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ adminRoute('roles.index') }}">角色</a></li>
            <li class="breadcrumb-item active" aria-current="page">edit</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <form action="{{ adminRoute('roles.update',$role->id) }}" method="POST">
                @csrf
                @method('put')
                <x-adminlte-input label="name" name="name" value="{{ old('name',$role->name) }}"></x-adminlte-input>
                <x-adminlte-button type="submit" label="submit"></x-adminlte-button>
            </form>
        </div>
    </div>

@stop




