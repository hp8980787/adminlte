@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>角色</h1>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <form action="{{ adminRoute('roles.update',$role->id) }}" method="POST">
                @csrf
                @method('put')
                <x-adminlte-input label="name" value="{{ $role->name }}"></x-adminlte-input>
            </form>
        </div>
    </div>


@stop




