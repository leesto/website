@extends('app')

@section('title', $page->title)

@section('content')
    @if($page->slug != 'home')
    <h1 class="slim deco">{{ $page->title }}</h1>
    @endif
    {!! $page->content !!}
@endsection