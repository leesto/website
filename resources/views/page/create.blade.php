@extends('app')

@section('title', 'Create a Page')

@section('content')
    <h1 class="slim">Create a New Page</h1>
    {!! Form::model($page = new \App\Page(['published' => 1]), ['url' => 'page', 'style' => 'max-width:700px;']) !!}
    <p>Use this form to create a new static HTML webpage.</p>
    @include('page.form')

    <div class="form-group">
        <button class="btn btn-success" disable-submit="Processing ..." type="submit">
            <span class="fa fa-check"></span>
            <span>Create Page</span>
        </button>
        <a class="btn btn-danger" href="{{ route('home') }}">
            <span class="fa fa-undo"></span>
            <span>Cancel</span>
        </a>
    </div>
    {!! Form::close() !!}
@endsection