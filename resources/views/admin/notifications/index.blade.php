@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>通知</h1>
@stop

@section('content')
    <div class="container">

        <x-adminlte-card title="通知" theme="teal" theme-mode="outline"
                         class="elevation-3" body-class="bg-light" header-class="bg-dark"
                         footer-class="bg-teal border-top rounded border-light"
                         icon="fas fa-lg fa-bell" collapsible removable maximizable>
            <x-slot name="toolsSlot">

            </x-slot>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                             aria-orientation="vertical">
                            @foreach($categories as $key=> $category)

                                @if($category->type=='App\Notifications\CreatePurchase'&&$category->notifiable_id==auth()->user()->id)
                                    <a class="nav-link @if($category->notifiable_id==auth()->user()->id) active @endif" id="v-tab-{{ $key }}"
                                       data-toggle="pill" href="#v-pills-home-{{ $key }}" role="tab"
                                       aria-controls="v-pills-home" aria-selected="true">采购消息 <i class="fas fa-bell"></i>{{ $category->nums }} </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            @foreach($categories as $key=> $category)

                                @if($category->notifiable_id==auth()->user()->id)
                                <div class="tab-pane fade @if($category->notifiable_id==auth()->user()->id) show active @endif "
                                     id="v-pills-home-{{ $key }}" role="tabpanel"
                                     aria-labelledby="v-pills-home-tab">
                                    <ul class="list-group">
                                        @foreach($notifications as $notification)
                                            @if($notification->type ==$category->type)
                                                @php
                                                    $notification->markAsRead();
                                                @endphp
                                                <li class="list-group-item mb-3 @if($notification->read_at) bg-gradient-gray @endif">
                                                  <div class="row">
                                                      <div class="col-md-4">
                                                          {{ $notification->data['purchase_id'] }}
                                                      </div>
                                                      <div class="col-md-4">
                                                          sadsads
                                                      </div>
                                                      <div class="col-md-4 text-xl-right">
                                                          @if($notification->read_at)
                                                            已读
                                                          @else
                                                            未读
                                                          @endif
                                                      </div>
                                                  </div>

                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="d-flex ml-auto" theme="light" label="submit"
                                   icon="fas fa-sign-in"/>
            </x-slot>
        </x-adminlte-card>
    </div>
@stop

@section('css')
<style>
    .nav-pills .nav-link.active {
        color: white;
        background-color: #20c997!important;
    }
</style>
@stop

@section('js')
    <script>

    </script>
@stop

@section('plugins.BsCustomFileInput', true)
@section('plugins.Summernote', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Listbox',true)
@section('plugins.BootstrapTable',true)
