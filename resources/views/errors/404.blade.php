@extends('error')

@section('title', 'Page not found')
@section('subtitle', 'We couldn\'t find that')
@section('num', 404)
@section('description')
    <p>The page you requested couldn't be found. It's possible that it's been moved but please check the URL and try again.</p>
    <p>Alternatively, you can go to the <a href="{{ route('home') }}">home page</a> and try navigating to it.</p>
@endsection