@extends('app')

@section('content')
    <h1 class="page-header">Contact Us</h1>
    <div class="tabpanel">
        {!! $menu !!}
        <div class="tab-content">
            <div class="tab-pane active">
                @yield('tab')
            </div>
            <div class="tab-pane"></div>
            <div class="tab-pane"></div>
        </div>
    </div>
@endsection