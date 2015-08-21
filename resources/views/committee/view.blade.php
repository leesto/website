@extends('app')

@section('title', 'The Committee')

@section('stylesheets')
    @include('partials.tags.style', ['path' => 'partials/committee'])
@endsection

@section('javascripts')
    @include('partials.tags.script', ['path' => 'partials/committee']);
@endsection

@section('content')
    <h1 class="page-header">The Committee</h1>
    <div id="viewCommittee">
        @if(count($roles))
            <div class="container-fluid">
                @for($i = 0; $i < ceil(count($roles) / 2); $i++)
                    <div class="row">
                        @include('committee._position', ['role' => $roles[2 * $i]])
                        @include('committee._position', ['role' => isset($roles[2 * $i + 1]) ? $roles[2 * $i + 1] : null])
                    </div>
                @endfor
            </div>
        @else
            <h4 class="no-committee">We don't seem to have any committee roles ...</h4>
        @endif
        @if(Auth::check() && Auth::user()->can('admin'))
            <hr>
            <a class="btn btn-success" data-toggle="modal" data-target="#roleModal" data-url="{{ route('committee.add') }}" data-method="add" href="#">
                <span class="fa fa-plus"></span>
                <span>Add a new role</span>
            </a>
        @endif
    </div>
@endsection

@section('modal')
    @if(Auth::check() && Auth::user()->can('admin'))
    @include('committee.form')
    @endif
@endsection