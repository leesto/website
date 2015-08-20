@extends('app')

@section('title', 'Create a Page')

@section('content')
    <h1 class="page-header">Create a New Page</h1>
    {!! Form::model($page = new \App\Page(['user_id' => Auth::user()->id, 'published' => 1]), ['route' => ['page.store'], 'style' => 'max-width:700px;']) !!}
    <p>Use this form to create a new static HTML webpage.</p>
    @include('pages.form')

    <div class="form-group">
        <button class="btn btn-success" disable-submit="Processing ..." type="submit">
            <span class="fa fa-check"></span>
            <span>Create Page</span>
        </button>
        <a class="btn btn-danger" href="{{ route('page.index') }}">
            <span class="fa fa-undo"></span>
            <span>Cancel</span>
        </a>
    </div>
    {!! Form::close() !!}
@endsection