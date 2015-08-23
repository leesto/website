@extends('app')

@section('content')
    <h1>{{ $event->name }}</h1>
    {{ var_dump($event) }}
@endsection