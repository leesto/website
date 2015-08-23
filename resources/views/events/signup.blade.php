@extends('app')

@section('title', 'Event Signup')

@section('scripts')
    $('#signup').tabify();
@endsection

@section('content')
    <h1 class="page-header">Event Signup</h1>
    <div class="tabpanel" id="signup">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#">Requiring an EM</a></li>
            <li><a href="#">Requiring Crew</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active">
                {{ var_dump($require_em) }}
            </div>
            <div class="tab-pane">
                {{ var_dump('not yet') }}
            </div>
        </div>
    </div>
@endsection