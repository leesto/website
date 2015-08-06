@extends('app')

@section('content')
    <div id="fatal-error" @if(app()->isDownForMaintenance() || isset($noNav)) {{ 'class=maintenance' }} @endif>
        <div class="header">
            <div class="num">@yield('num')</div>
            <div class="title">
                <h1>@yield('title')</h1>
                <h2>@yield('subtitle')</h2>
            </div>
        </div>
        <div class="description">
            @yield('description')
        </div>
        @if(env("APP_DEBUG", 0) && !app()->isDownForMaintenance() && !isset($noDebug))
            <div class="panel panel-info">
                <div class="panel-heading">Debug Trace</div>
                <div class="panel-body">
                    {!! nl2br($exception->getTraceAsString()) !!}
                </div>
            </div>
        @endif
    </div>
@endsection